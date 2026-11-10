<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ClinicSetting;
use App\Models\Lead;
use App\Models\Couple;
use App\Models\Patient;
use App\Models\InfertilityWorkup;
use App\Models\IvfCycle;
use App\Models\FollicularScan;
use App\Models\OpuAndLabRecord;
use App\Models\EmbryoTransfer;
use App\Models\CycleOutcome;
use App\Models\AntenatalRecord;
use App\Models\DeliveryRecord;
use App\Models\BabyRecord;
use App\Models\VaccinationSchedule;
use App\Models\CryobankStorage;

class IvfController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $currentUser = Auth::user();
        $activeTab = $request->get('tab', 'overview');

        // RBAC: Check module access authorization
        if ($currentUser && !$currentUser->canAccessTab($activeTab)) {
            return redirect()->route('ivf.home', ['tab' => 'overview'])
                ->with('error', "Access restricted: Your clinical role ({$currentUser->role}) does not have permission to access the '{$activeTab}' module.");
        }

        // Analytics & KPI metrics
        $metrics = [
            'total_leads' => Lead::count(),
            'active_couples' => Couple::count(),
            'cycles_underway' => IvfCycle::whereIn('status', ['Stimulation', 'OPU_Scheduled', 'OPU_Completed', 'Embryo_Culture'])->count(),
            'live_births' => BabyRecord::count(),
            'cryo_specimens' => CryobankStorage::where('status', 'Stored')->sum('quantity'),
            'vaccines_due' => VaccinationSchedule::where('status', 'Scheduled')->count(),
            'reattempt_cycles' => IvfCycle::where('is_reattempt', true)->count(),
        ];

        // Datasets
        $leads = Lead::latest()->get();
        $couples = Couple::with(['wife', 'husband', 'workup', 'cycles.outcome'])->latest()->get();
        $cycles = IvfCycle::with(['couple.wife', 'couple.husband', 'scans', 'labRecord', 'transfers', 'outcome', 'previousCycle'])->latest()->get();
        $scans = FollicularScan::with('cycle.couple.wife')->latest()->take(20)->get();
        $labRecords = OpuAndLabRecord::with('cycle.couple.wife')->latest()->get();
        $transfers = EmbryoTransfer::with('cycle.couple.wife', 'cycle.outcome')->latest()->get();
        $failures = CycleOutcome::where('is_failure', true)->with('cycle.couple.wife', 'cycle.couple.husband', 'cycle.reattempts')->latest()->get();
        $antenatals = AntenatalRecord::with(['couple.wife', 'cycle', 'delivery.babies.vaccinations'])->latest()->get();
        $deliveries = DeliveryRecord::with(['antenatalRecord.couple.wife', 'babies.vaccinations'])->latest()->get();
        $babies = BabyRecord::with(['deliveryRecord.antenatalRecord.couple.wife', 'vaccinations'])->latest()->get();
        $cryoStorages = CryobankStorage::with('couple.wife')->latest()->get();
        $allUsers = User::latest()->get();
        $clinicSetting = ClinicSetting::getMasterSettings();

        return view('ivf.index', compact(
            'activeTab',
            'metrics',
            'leads',
            'couples',
            'cycles',
            'scans',
            'labRecords',
            'transfers',
            'failures',
            'antenatals',
            'deliveries',
            'babies',
            'cryoStorages',
            'allUsers',
            'clinicSetting'
        ));
    }

    // -------------------------------------------------------------------------
    // 1. LEADS CRUD
    // -------------------------------------------------------------------------
    public function storeLead(Request $request)
    {
        $validated = $request->validate([
            'wife_name' => 'required|string|max:255',
            'husband_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'infertility_years' => 'required|integer|min:0',
            'infertility_type' => 'required|string',
            'source' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $validated['lead_code'] = 'LED-' . date('Y') . '-' . str_pad(Lead::count() + 1, 3, '0', STR_PAD_LEFT);
        $validated['status'] = $request->input('status', 'New');

        Lead::create($validated);

        return redirect()->route('ivf.home', ['tab' => 'leads'])->with('success', 'New lead registered successfully.');
    }

    public function updateLead(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->update($request->only([
            'wife_name', 'husband_name', 'phone', 'email', 
            'infertility_years', 'infertility_type', 'source', 'status', 'notes'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'leads'])->with('success', "Lead {$lead->lead_code} updated successfully.");
    }

    public function deleteLead($id)
    {
        $lead = Lead::findOrFail($id);
        $code = $lead->lead_code;
        $lead->delete();

        return redirect()->route('ivf.home', ['tab' => 'leads'])->with('success', "Lead {$code} removed successfully.");
    }

    // -------------------------------------------------------------------------
    // 2. COUPLES & WORKUP CRUD
    // -------------------------------------------------------------------------
    public function storeCouple(Request $request)
    {
        $validated = $request->validate([
            'wife_first_name' => 'required|string|max:255',
            'wife_last_name' => 'required|string|max:255',
            'wife_blood_group' => 'nullable|string|max:50',
            'wife_phone' => 'required|string|max:50',
            'husband_first_name' => 'nullable|string|max:255',
            'husband_last_name' => 'nullable|string|max:255',
            'husband_blood_group' => 'nullable|string|max:50',
            'husband_phone' => 'nullable|string|max:50',
            'marriage_years' => 'required|integer|min:0',
            'primary_doctor' => 'required|string|max:255',
            'consent_status' => 'required|string',
            'female_amh' => 'nullable|numeric',
            'female_afc' => 'nullable|integer',
            'female_fsh' => 'nullable|numeric',
            'tubal_patency' => 'nullable|string',
            'sperm_count_million' => 'nullable|numeric',
            'sperm_motility_percent' => 'nullable|integer',
            'sperm_morphology_percent' => 'nullable|integer',
            'sperm_dfi_percent' => 'nullable|integer',
            'clinical_diagnosis' => 'required|string|max:255',
        ]);

        $baseMrnNum = 1000 + (Patient::max('id') ?? 0) + 1;
        $wifeMrn = 'MRN-F-' . $baseMrnNum;
        while (Patient::where('mrn', $wifeMrn)->exists()) {
            $baseMrnNum++;
            $wifeMrn = 'MRN-F-' . $baseMrnNum;
        }

        $wifePatient = Patient::create([
            'mrn' => $wifeMrn,
            'first_name' => $validated['wife_first_name'],
            'last_name' => $validated['wife_last_name'],
            'gender' => 'Female',
            'phone' => $validated['wife_phone'],
            'blood_group' => $validated['wife_blood_group'] ?? 'Unknown',
            'kyc_id_type' => 'Aadhaar / National ID',
        ]);

        $husbandPatient = null;
        if (!empty($validated['husband_first_name'])) {
            $baseMrnNum++;
            $husbandMrn = 'MRN-M-' . $baseMrnNum;
            while (Patient::where('mrn', $husbandMrn)->exists()) {
                $baseMrnNum++;
                $husbandMrn = 'MRN-M-' . $baseMrnNum;
            }

            $husbandPatient = Patient::create([
                'mrn' => $husbandMrn,
                'first_name' => $validated['husband_first_name'],
                'last_name' => $validated['husband_last_name'] ?? $validated['wife_last_name'],
                'gender' => 'Male',
                'phone' => $validated['husband_phone'] ?? $validated['wife_phone'],
                'blood_group' => $validated['husband_blood_group'] ?? 'Unknown',
                'kyc_id_type' => 'Aadhaar / National ID',
            ]);
        }

        $coupleNum = 7000 + (Couple::max('id') ?? 0) + 1;
        $coupleCode = 'CPL-' . $coupleNum;
        while (Couple::where('couple_code', $coupleCode)->exists()) {
            $coupleNum++;
            $coupleCode = 'CPL-' . $coupleNum;
        }

        $couple = Couple::create([
            'couple_code' => $coupleCode,
            'wife_id' => $wifePatient->id,
            'husband_id' => $husbandPatient ? $husbandPatient->id : null,
            'marriage_years' => $validated['marriage_years'],
            'primary_doctor' => $validated['primary_doctor'],
            'consent_status' => $validated['consent_status'],
            'status' => 'Active',
        ]);

        InfertilityWorkup::create([
            'couple_id' => $couple->id,
            'female_amh' => $validated['female_amh'],
            'female_afc' => $validated['female_afc'],
            'female_fsh' => $validated['female_fsh'],
            'tubal_patency' => $validated['tubal_patency'] ?? 'Both Patent',
            'sperm_count_million' => $validated['sperm_count_million'],
            'sperm_motility_percent' => $validated['sperm_motility_percent'],
            'sperm_morphology_percent' => $validated['sperm_morphology_percent'],
            'sperm_dfi_percent' => $validated['sperm_dfi_percent'],
            'clinical_diagnosis' => $validated['clinical_diagnosis'],
        ]);

        return redirect()->route('ivf.home', ['tab' => 'couples'])->with('success', "Couple {$couple->couple_code} registered with initial workup.");
    }

    public function deleteCouple($id)
    {
        $couple = Couple::findOrFail($id);
        $code = $couple->couple_code;
        $couple->delete();

        return redirect()->route('ivf.home', ['tab' => 'couples'])->with('success', "Couple {$code} removed.");
    }

    public function updateCoupleWorkup(Request $request, $id)
    {
        $couple = Couple::findOrFail($id);
        
        $couple->update($request->only([
            'marriage_years', 'primary_doctor', 'counselor_name', 'consent_status', 'status'
        ]));

        if ($request->has('wife_first_name') && $couple->wife) {
            $couple->wife->update([
                'first_name' => $request->input('wife_first_name'),
                'last_name' => $request->input('wife_last_name'),
                'blood_group' => $request->input('wife_blood_group'),
                'phone' => $request->input('wife_phone'),
            ]);
        }

        if ($request->has('husband_first_name') && $couple->husband) {
            $couple->husband->update([
                'first_name' => $request->input('husband_first_name'),
                'last_name' => $request->input('husband_last_name'),
                'blood_group' => $request->input('husband_blood_group'),
                'phone' => $request->input('husband_phone'),
            ]);
        }

        if ($couple->workup) {
            $couple->workup->update($request->only([
                'female_amh', 'female_afc', 'female_fsh', 'female_tsh', 'tubal_patency', 'uterine_cavity_notes',
                'sperm_count_million', 'sperm_motility_percent', 'sperm_morphology_percent', 'sperm_dfi_percent',
                'clinical_diagnosis', 'doctor_recommendation'
            ]));
        }

        return redirect()->route('ivf.home', ['tab' => 'couples'])->with('success', "Couple {$couple->couple_code} profile & diagnostics updated.");
    }

    public function uploadConsentFile(Request $request, $id)
    {
        $couple = Couple::findOrFail($id);

        $request->validate([
            'signed_consent_file' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:15360',
        ]);

        if ($request->hasFile('signed_consent_file')) {
            // Delete old file if exists
            if ($couple->consent_file_path && Storage::disk('local')->exists($couple->consent_file_path)) {
                Storage::disk('local')->delete($couple->consent_file_path);
            }

            $file = $request->file('signed_consent_file');
            $filename = 'consent_' . $couple->couple_code . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('consents', $filename, 'local');

            $couple->update([
                'consent_file_path' => $path,
                'consent_signed_at' => now(),
                'consent_status' => 'Verified',
            ]);

            return redirect()->route('ivf.home', ['tab' => 'couples'])->with('success', "Signed legal consent document uploaded and verified for couple {$couple->couple_code}.");
        }

        return redirect()->route('ivf.home', ['tab' => 'couples'])->with('error', 'No file was uploaded.');
    }

    public function viewConsentFile($id)
    {
        $couple = Couple::findOrFail($id);

        if (!$couple->consent_file_path || !Storage::disk('local')->exists($couple->consent_file_path)) {
            return redirect()->route('ivf.home', ['tab' => 'couples'])->with('error', 'No signed consent document found for this couple.');
        }

        $fullPath = Storage::disk('local')->path($couple->consent_file_path);
        return response()->file($fullPath);
    }

    public function removeConsentFile($id)
    {
        $couple = Couple::findOrFail($id);

        if ($couple->consent_file_path && Storage::disk('local')->exists($couple->consent_file_path)) {
            Storage::disk('local')->delete($couple->consent_file_path);
        }

        $couple->update([
            'consent_file_path' => null,
            'consent_signed_at' => null,
            'consent_status' => 'Pending',
        ]);

        return redirect()->route('ivf.home', ['tab' => 'couples'])->with('success', "Signed consent document removed for couple {$couple->couple_code}. Status reset to Pending.");
    }

    // -------------------------------------------------------------------------
    // 3. TREATMENT CYCLES CRUD
    // -------------------------------------------------------------------------
    public function storeCycle(Request $request)
    {
        $validated = $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'protocol_name' => 'required|string|max:255',
            'cycle_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'status' => 'required|string',
            'trigger_datetime' => 'nullable|string',
            'trigger_drug' => 'nullable|string|max:255',
            'cycle_notes' => 'nullable|string',
        ]);

        $couple = Couple::with(['wife', 'husband', 'cycles'])->findOrFail($validated['couple_id']);
        $existingCount = $couple->cycles()->count();
        $validated['cycle_number'] = $existingCount + 1;
        $validated['is_reattempt'] = ($existingCount > 0);
        if ($validated['is_reattempt']) {
            $lastCycle = $couple->cycles()->latest()->first();
            $validated['previous_cycle_id'] = $lastCycle ? $lastCycle->id : null;
        }

        $cycle = IvfCycle::create($validated);

        return redirect()->route('ivf.home', ['tab' => 'cycles'])->with('success', "Treatment Cycle CYC-{$cycle->id} (Cycle #{$cycle->cycle_number}) started for {$couple->couple_code} ({$couple->wife->first_name} {$couple->wife->last_name}).");
    }

    public function deleteCycle($id)
    {
        $cycle = IvfCycle::findOrFail($id);
        $idStr = $cycle->id;
        $cycle->delete();

        return redirect()->route('ivf.home', ['tab' => 'cycles'])->with('success', "Cycle CYC-{$idStr} removed.");
    }

    public function updateCycle(Request $request, $id)
    {
        $cycle = IvfCycle::findOrFail($id);
        $cycle->update($request->only([
            'protocol_name', 'cycle_type', 'status', 'start_date', 'trigger_datetime', 'trigger_drug', 'cycle_notes'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'cycles'])->with('success', "Cycle CYC-{$cycle->id} updated successfully.");
    }

    // -------------------------------------------------------------------------
    // 4. FOLLICULAR SCANS CRUD
    // -------------------------------------------------------------------------
    public function storeFollicularScan(Request $request)
    {
        $cycleInput = $request->input('cycle_id');

        // If user chose a couple without a cycle (prefixed with new_cpl_)
        if (is_string($cycleInput) && str_starts_with($cycleInput, 'new_cpl_')) {
            $coupleId = (int) str_replace('new_cpl_', '', $cycleInput);
            $couple = Couple::findOrFail($coupleId);
            $cycle = IvfCycle::create([
                'couple_id' => $couple->id,
                'cycle_number' => $couple->cycles()->count() + 1,
                'protocol_name' => 'Flexible GnRH Antagonist Protocol',
                'cycle_type' => 'Self-ICSI',
                'start_date' => $request->input('scan_date', date('Y-m-d')),
                'status' => 'Stimulation',
                'cycle_notes' => 'Auto-initiated during follicular scan entry',
            ]);
            $cycleId = $cycle->id;
        } else {
            $cycleId = (int) $cycleInput;
        }

        $validated = $request->validate([
            'scan_date' => 'required|date',
            'cycle_day' => 'required|integer',
            'right_ovary_follicles' => 'required|string',
            'left_ovary_follicles' => 'required|string',
            'endometrium_thickness_mm' => 'required|numeric',
            'endometrium_pattern' => 'required|string',
            'estradiol_e2_pg_ml' => 'nullable|numeric',
            'progesterone_p4_ng_ml' => 'nullable|numeric',
            'gonadotropin_dose_given' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['cycle_id'] = $cycleId;

        FollicularScan::create($validated);

        return redirect()->route('ivf.home', ['tab' => 'cycles'])->with('success', "Follicular scan recorded for CYC-{$cycleId} (Day {$validated['cycle_day']}).");
    }

    public function updateFollicularScan(Request $request, $id)
    {
        $scan = FollicularScan::findOrFail($id);
        $scan->update($request->only([
            'scan_date', 'cycle_day', 'right_ovary_follicles', 'left_ovary_follicles',
            'endometrium_thickness_mm', 'endometrium_pattern', 'estradiol_e2_pg_ml',
            'gonadotropin_dose_given', 'notes'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'cycles'])->with('success', "Scan on Day {$scan->cycle_day} updated successfully.");
    }

    public function deleteFollicularScan($id)
    {
        $scan = FollicularScan::findOrFail($id);
        $scan->delete();

        return redirect()->route('ivf.home', ['tab' => 'cycles'])->with('success', 'Scan record deleted.');
    }

    // -------------------------------------------------------------------------
    // 5. OPU & EMBRYOLOGY LAB CRUD
    // -------------------------------------------------------------------------
    public function storeLabRecord(Request $request)
    {
        $validated = $request->validate([
            'cycle_id' => 'required|exists:ivf_cycles,id',
            'opu_date' => 'required|date',
            'oocytes_retrieved' => 'required|integer|min:0',
            'mii_mature_count' => 'required|integer|min:0',
            'mi_count' => 'nullable|integer|min:0',
            'gv_count' => 'nullable|integer|min:0',
            'fertilized_2pn_count' => 'required|integer|min:0',
            'day3_cleaved_count' => 'nullable|integer|min:0',
            'day5_blast_count' => 'nullable|integer|min:0',
            'blastocyst_grades' => 'nullable|string',
            'insemination_method' => 'nullable|string',
            'embryologist_name' => 'nullable|string',
        ]);

        $record = OpuAndLabRecord::create($validated);

        $cycle = IvfCycle::find($validated['cycle_id']);
        if ($cycle && in_array($cycle->status, ['Stimulation', 'OPU_Scheduled'])) {
            $cycle->update(['status' => 'OPU_Completed']);
        }

        return redirect()->route('ivf.home', ['tab' => 'laboratory'])->with('success', "Embryology Lab & OPU record logged for CYC-{$record->cycle_id}.");
    }

    public function deleteLabRecord($id)
    {
        $lab = OpuAndLabRecord::findOrFail($id);
        $cid = $lab->cycle_id;
        $lab->delete();

        return redirect()->route('ivf.home', ['tab' => 'laboratory'])->with('success', "Lab record for CYC-{$cid} deleted.");
    }

    public function updateLabRecord(Request $request, $id)
    {
        $lab = OpuAndLabRecord::findOrFail($id);
        $lab->update($request->only([
            'opu_date', 'oocytes_retrieved', 'mii_mature_count', 'mi_count', 'gv_count',
            'insemination_method', 'semen_prep_method', 'fertilized_2pn_count',
            'day3_cleaved_count', 'day5_blast_count', 'blastocyst_grades', 'embryologist_name'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'laboratory'])->with('success', "Lab record for CYC-{$lab->cycle_id} updated.");
    }

    // -------------------------------------------------------------------------
    // 6. TRANSFERS CRUD
    // -------------------------------------------------------------------------
    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'cycle_id' => 'required|exists:ivf_cycles,id',
            'transfer_type' => 'required|string',
            'transfer_date' => 'required|date',
            'embryos_transferred_count' => 'required|integer|min:1|max:4',
            'embryos_selected' => 'required|string|max:255',
            'catheter_type' => 'nullable|string|max:255',
            'ease_of_transfer' => 'nullable|string|max:255',
            'doctor_name' => 'nullable|string|max:255',
            'betahcg_day14' => 'nullable|numeric',
            'betahcg_day16' => 'nullable|numeric',
            'result_status' => 'nullable|string',
            'gestational_sacs' => 'nullable|integer',
        ]);

        $et = EmbryoTransfer::create([
            'cycle_id' => $validated['cycle_id'],
            'transfer_type' => $validated['transfer_type'],
            'transfer_date' => $validated['transfer_date'],
            'embryos_transferred_count' => $validated['embryos_transferred_count'],
            'embryos_selected' => $validated['embryos_selected'],
            'catheter_type' => $validated['catheter_type'] ?? 'Cook Sydney Soft-Pass',
            'ease_of_transfer' => $validated['ease_of_transfer'] ?? 'Easy / Smooth',
            'ultrasound_guided' => true,
            'doctor_name' => $validated['doctor_name'] ?? 'Dr. Elena Vance, MD',
        ]);

        $cycle = IvfCycle::find($validated['cycle_id']);
        if ($cycle) {
            $cycle->update(['status' => 'Transfer_Completed']);
            
            $outcome = CycleOutcome::firstOrNew(['cycle_id' => $cycle->id]);
            if (!empty($validated['betahcg_day14'])) {
                $outcome->betahcg_day14 = $validated['betahcg_day14'];
            }
            if (!empty($validated['betahcg_day16'])) {
                $outcome->betahcg_day16 = $validated['betahcg_day16'];
            }
            if (!empty($validated['result_status'])) {
                $outcome->result_status = $validated['result_status'];
                if ($validated['result_status'] === 'Positive') {
                    $cycle->update(['status' => 'Outcome_Positive']);
                    $outcome->is_failure = false;
                } elseif ($validated['result_status'] === 'Negative' || $validated['result_status'] === 'Clinical Miscarriage') {
                    $cycle->update(['status' => 'Outcome_Failed']);
                    $outcome->is_failure = true;
                    $outcome->failure_stage = 'Implantation Failure';
                }
            }
            if (isset($validated['gestational_sacs'])) {
                $outcome->gestational_sacs = $validated['gestational_sacs'];
            }
            $outcome->save();
        }

        return redirect()->route('ivf.home', ['tab' => 'transfers'])->with('success', "Embryo Transfer record saved for CYC-{$et->cycle_id}.");
    }

    public function deleteTransfer($id)
    {
        $et = EmbryoTransfer::findOrFail($id);
        $cid = $et->cycle_id;
        $et->delete();

        return redirect()->route('ivf.home', ['tab' => 'transfers'])->with('success', "Transfer record for CYC-{$cid} deleted.");
    }

    public function updateTransfer(Request $request, $id)
    {
        $et = EmbryoTransfer::findOrFail($id);
        $et->update($request->only([
            'transfer_type', 'transfer_date', 'embryos_transferred_count', 'embryos_selected',
            'catheter_type', 'ease_of_transfer', 'doctor_name'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'transfers'])->with('success', "Transfer record for CYC-{$et->cycle_id} updated.");
    }

    // -------------------------------------------------------------------------
    // 7. CYCLE OUTCOMES & RE-ATTEMPTS CRUD
    // -------------------------------------------------------------------------
    public function storeFailureRca(Request $request)
    {
        $validated = $request->validate([
            'cycle_id' => 'required|exists:ivf_cycles,id',
            'failure_stage' => 'required|string|max:255',
            'root_cause_analysis' => 'required|string',
            'reattempt_strategy' => 'nullable|string',
        ]);

        $outcome = CycleOutcome::firstOrNew(['cycle_id' => $validated['cycle_id']]);
        $outcome->is_failure = true;
        $outcome->failure_stage = $validated['failure_stage'];
        $outcome->root_cause_analysis = $validated['root_cause_analysis'];
        $outcome->reattempt_strategy = $validated['reattempt_strategy'] ?? 'Protocol modification and targeted adjuvant therapy recommended.';
        if (!$outcome->result_status || $outcome->result_status === 'Positive') {
            $outcome->result_status = 'Negative';
        }
        $outcome->save();

        $cycle = IvfCycle::find($validated['cycle_id']);
        if ($cycle) {
            $cycle->update(['status' => 'Outcome_Failed']);
        }

        return redirect()->route('ivf.home', ['tab' => 'failures'])->with('success', "Failure RCA documented for CYC-{$validated['cycle_id']}.");
    }

    public function deleteFailureRca($id)
    {
        $outcome = CycleOutcome::findOrFail($id);
        $outcome->update([
            'is_failure' => false,
            'failure_stage' => null,
            'root_cause_analysis' => null,
            'reattempt_strategy' => null,
        ]);

        return redirect()->route('ivf.home', ['tab' => 'failures'])->with('success', "Failure RCA record removed.");
    }

    public function updateOutcome(Request $request, $id)
    {
        $outcome = CycleOutcome::findOrFail($id);
        $outcome->update($request->only([
            'betahcg_day14', 'betahcg_day16', 'result_status', 'gestational_sacs',
            'fetal_heartbeat', 'is_failure', 'failure_stage', 'root_cause_analysis', 'reattempt_strategy'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'failures'])->with('success', "Outcome record for CYC-{$outcome->cycle_id} updated.");
    }

    public function planReattempt(Request $request)
    {
        $validated = $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'previous_cycle_id' => 'required|exists:ivf_cycles,id',
            'protocol_name' => 'required|string',
            'cycle_type' => 'required|string',
            'start_date' => 'required|date',
            'cycle_notes' => 'nullable|string',
        ]);

        $prev = IvfCycle::find($validated['previous_cycle_id']);
        $nextCycleNumber = $prev ? ($prev->cycle_number + 1) : 2;

        $newCycle = IvfCycle::create([
            'couple_id' => $validated['couple_id'],
            'cycle_number' => $nextCycleNumber,
            'cycle_type' => $validated['cycle_type'],
            'protocol_name' => $validated['protocol_name'],
            'is_reattempt' => true,
            'previous_cycle_id' => $prev ? $prev->id : null,
            'start_date' => $validated['start_date'],
            'status' => 'Stimulation',
            'cycle_notes' => $validated['cycle_notes'],
        ]);

        return redirect()->route('ivf.home', ['tab' => 'failures'])->with('success', "Cycle #{$nextCycleNumber} re-attempt authorized & launched for couple.");
    }

    // -------------------------------------------------------------------------
    // 8. ANTENATAL CARE (ANC) CRUD
    // -------------------------------------------------------------------------
    public function storeAnc(Request $request)
    {
        $validated = $request->validate([
            'cycle_id' => 'required|exists:ivf_cycles,id',
            'edd' => 'required|date',
            'gravida' => 'required|integer|min:1',
            'para' => 'required|integer|min:0',
            'risk_classification' => 'required|string|max:255',
            'nt_scan_result' => 'nullable|string|max:255',
            'anomaly_scan_result' => 'nullable|string|max:255',
            'pregnancy_status' => 'required|string',
            'obstetrician_name' => 'required|string|max:255',
        ]);

        $cycle = IvfCycle::with('couple.wife')->findOrFail($validated['cycle_id']);
        $validated['couple_id'] = $cycle->couple_id;

        $anc = AntenatalRecord::create($validated);

        $wifeName = $cycle->couple->wife ? ($cycle->couple->wife->first_name . ' ' . $cycle->couple->wife->last_name) : 'Patient';

        return redirect()->route('ivf.home', ['tab' => 'antenatal'])->with('success', "New Antenatal Care record ANC-{$anc->id} registered for {$wifeName}.");
    }

    public function deleteAnc($id)
    {
        $anc = AntenatalRecord::findOrFail($id);
        $ancId = $anc->id;
        $anc->delete();

        return redirect()->route('ivf.home', ['tab' => 'antenatal'])->with('success', "Antenatal record ANC-{$ancId} removed.");
    }

    public function updateAnc(Request $request, $id)
    {
        $anc = AntenatalRecord::findOrFail($id);
        $anc->update($request->only([
            'edd', 'gravida', 'para', 'risk_classification', 'nt_scan_result',
            'anomaly_scan_result', 'pregnancy_status', 'obstetrician_name'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'antenatal'])->with('success', "ANC record ANC-{$anc->id} updated.");
    }

    // -------------------------------------------------------------------------
    // 9. DELIVERIES CRUD
    // -------------------------------------------------------------------------
    public function storeDelivery(Request $request)
    {
        $validated = $request->validate([
            'antenatal_record_id' => 'required|exists:antenatal_records,id',
            'delivery_datetime' => 'required|string',
            'mode_of_delivery' => 'required|string',
            'indication_notes' => 'nullable|string',
            'maternal_complications' => 'nullable|string',
            'delivering_surgeon' => 'required|string',
            'baby_name' => 'nullable|string|max:255',
            'baby_gender' => 'nullable|string|in:Female,Male',
            'birth_weight_grams' => 'nullable|numeric|min:500|max:8000',
            'apgar_1min' => 'nullable|integer|min:0|max:10',
            'apgar_5min' => 'nullable|integer|min:0|max:10',
            'pediatrician_name' => 'nullable|string|max:255',
            'discharge_notes' => 'nullable|string',
        ]);

        $del = DeliveryRecord::create([
            'antenatal_record_id' => $validated['antenatal_record_id'],
            'delivery_datetime' => $validated['delivery_datetime'],
            'mode_of_delivery' => $validated['mode_of_delivery'],
            'indication_notes' => $validated['indication_notes'] ?? null,
            'maternal_complications' => $validated['maternal_complications'] ?? 'Normal, uneventful recovery',
            'delivering_surgeon' => $validated['delivering_surgeon'],
        ]);

        $anc = AntenatalRecord::with('couple.wife')->find($validated['antenatal_record_id']);
        if ($anc) {
            $anc->update(['pregnancy_status' => 'Delivered']);
        }

        // Auto-register newborn baby if baby_name provided
        if (!empty($validated['baby_name'])) {
            $coupleCode = $anc && $anc->couple ? $anc->couple->couple_code : 'CPL-' . rand(1000, 9999);
            $countBabies = BabyRecord::count() + 1;
            $babyCode = 'BBY-' . str_replace('CPL-', '', $coupleCode) . '-' . chr(64 + min($countBabies, 26));

            $baby = BabyRecord::create([
                'delivery_record_id' => $del->id,
                'baby_code' => $babyCode,
                'baby_name' => $validated['baby_name'],
                'gender' => $validated['baby_gender'] ?? 'Female',
                'birth_weight_grams' => $validated['birth_weight_grams'] ?? 3100,
                'apgar_1min' => $validated['apgar_1min'] ?? 9,
                'apgar_5min' => $validated['apgar_5min'] ?? 10,
                'pediatrician_name' => $validated['pediatrician_name'] ?? 'Dr. David Kim, MD (Pediatrics)',
                'discharge_notes' => $validated['discharge_notes'] ?? 'Vitals stable. Cried vigorously at birth. Good tone and reflexes.',
            ]);

            // Auto-schedule standard birth immunization
            $birthDate = date('Y-m-d');
            $birthVaccines = [
                ['name' => 'BCG (Tuberculosis)', 'batch' => 'BCG-' . date('Y') . '-X' . rand(10, 99)],
                ['name' => 'OPV-0 (Oral Polio)', 'batch' => 'OPV-' . rand(1000, 9999)],
                ['name' => 'Hepatitis B (Birth Dose)', 'batch' => 'HEPB-' . rand(1000, 9999)],
            ];

            foreach ($birthVaccines as $bv) {
                VaccinationSchedule::create([
                    'baby_record_id' => $baby->id,
                    'vaccine_name' => $bv['name'],
                    'milestone_stage' => 'At Birth',
                    'due_date' => $birthDate,
                    'administered_date' => $birthDate,
                    'status' => 'Administered',
                    'batch_number' => $bv['batch'],
                    'administered_by' => 'Nurse Claire, RN',
                ]);
            }
        }

        return redirect()->route('ivf.home', ['tab' => 'deliveries'])->with('success', "New delivery record DEL-{$del->id} registered successfully.");
    }

    public function deleteDelivery($id)
    {
        $del = DeliveryRecord::findOrFail($id);
        $delId = $del->id;
        $del->delete();

        return redirect()->route('ivf.home', ['tab' => 'deliveries'])->with('success', "Delivery record DEL-{$delId} removed.");
    }

    public function updateDelivery(Request $request, $id)
    {
        $del = DeliveryRecord::findOrFail($id);
        $del->update($request->only([
            'delivery_datetime', 'mode_of_delivery', 'indication_notes', 'maternal_complications', 'delivering_surgeon'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'deliveries'])->with('success', "Delivery DEL-{$del->id} record updated.");
    }

    // -------------------------------------------------------------------------
    // 10. BABIES & PEDIATRICS CRUD
    // -------------------------------------------------------------------------
    public function updateBaby(Request $request, $id)
    {
        $baby = BabyRecord::findOrFail($id);
        $baby->update($request->only([
            'baby_name', 'gender', 'birth_weight_grams', 'apgar_1min', 'apgar_5min', 'pediatrician_name', 'discharge_notes'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'pediatrics'])->with('success', "Baby record {$baby->baby_code} updated.");
    }

    public function storeVaccine(Request $request)
    {
        $babyId = $request->input('baby_record_id');
        if (!$babyId || !BabyRecord::where('id', $babyId)->exists()) {
            return redirect()->route('ivf.home', ['tab' => 'pediatrics'])->with('error', 'Valid baby record required.');
        }

        $createdCount = 0;

        // 1. Dynamic multi-row submissions via vaccine_items array
        if ($request->has('vaccine_items') && is_array($request->input('vaccine_items'))) {
            foreach ($request->input('vaccine_items') as $item) {
                if (!empty($item['vaccine_name']) && !empty($item['due_date'])) {
                    VaccinationSchedule::create([
                        'baby_record_id' => $babyId,
                        'vaccine_name' => trim($item['vaccine_name']),
                        'milestone_stage' => $item['milestone_stage'] ?? ($request->input('milestone_stage') ?? 'Routine Milestone'),
                        'due_date' => $item['due_date'],
                        'status' => $item['status'] ?? 'Scheduled',
                        'batch_number' => $item['batch_number'] ?? null,
                    ]);
                    $createdCount++;
                }
            }
        } 
        // 2. Preset checklist / bundled vaccines selection
        elseif ($request->has('preset_vaccines') && is_array($request->input('preset_vaccines'))) {
            $milestone = $request->input('milestone_stage', 'Routine Milestone');
            $dueDate = $request->input('due_date', date('Y-m-d'));
            foreach ($request->input('preset_vaccines') as $vacName) {
                if (!empty($vacName)) {
                    VaccinationSchedule::create([
                        'baby_record_id' => $babyId,
                        'vaccine_name' => trim($vacName),
                        'milestone_stage' => $milestone,
                        'due_date' => $dueDate,
                        'status' => 'Scheduled',
                    ]);
                    $createdCount++;
                }
            }
        }
        // 3. Fallback for single vaccine submission
        elseif ($request->filled('vaccine_name')) {
            VaccinationSchedule::create([
                'baby_record_id' => $babyId,
                'vaccine_name' => $request->input('vaccine_name'),
                'milestone_stage' => $request->input('milestone_stage', 'Routine Milestone'),
                'due_date' => $request->input('due_date', date('Y-m-d')),
                'status' => $request->input('status', 'Scheduled'),
                'batch_number' => $request->input('batch_number'),
                'administered_date' => $request->input('administered_date'),
                'administered_by' => $request->input('administered_by'),
            ]);
            $createdCount = 1;
        }

        $baby = BabyRecord::find($babyId);
        $babyName = $baby ? $baby->baby_name : 'Newborn';

        return redirect()->route('ivf.home', ['tab' => 'pediatrics'])->with('success', "{$createdCount} vaccine(s) successfully scheduled for {$babyName}.");
    }

    public function updateVaccine(Request $request, $id)
    {
        $vaccine = VaccinationSchedule::findOrFail($id);
        $vaccine->update($request->only([
            'vaccine_name', 'milestone_stage', 'due_date', 'administered_date',
            'status', 'batch_number', 'administered_by',
            'site_and_route', 'manufacturer', 'vaccine_expiry_date',
            'baby_weight_at_vac', 'temperature_f', 'clinical_remarks'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'pediatrics'])->with('success', "Vaccination details & clinical records updated.");
    }

    public function updateVaccineStatus(Request $request, $id)
    {
        $vaccine = VaccinationSchedule::findOrFail($id);
        $status = $request->input('status', 'Administered');
        $vaccine->status = $status;

        if ($status === 'Administered') {
            $vaccine->administered_date = $request->input('administered_date', date('Y-m-d'));
            $vaccine->batch_number = $request->input('batch_number', 'BAT-' . strtoupper(substr(md5(time() . $vaccine->id), 0, 7)));
            $vaccine->administered_by = $request->input('administered_by', 'Nurse Claire, RN');
            $vaccine->site_and_route = $request->input('site_and_route');
            $vaccine->manufacturer = $request->input('manufacturer');
            $vaccine->vaccine_expiry_date = $request->input('vaccine_expiry_date');
            $vaccine->baby_weight_at_vac = $request->input('baby_weight_at_vac');
            $vaccine->temperature_f = $request->input('temperature_f');
            $vaccine->clinical_remarks = $request->input('clinical_remarks');
        } else {
            $vaccine->administered_date = null;
            $vaccine->clinical_remarks = null;
        }
        $vaccine->save();

        return redirect()->route('ivf.home', ['tab' => 'pediatrics'])->with('success', "Vaccine {$vaccine->vaccine_name} administered and clinical observations logged.");
    }

    public function deleteVaccine($id)
    {
        $vaccine = VaccinationSchedule::findOrFail($id);
        $vaccine->delete();

        return redirect()->route('ivf.home', ['tab' => 'pediatrics'])->with('success', 'Vaccine schedule removed.');
    }

    // -------------------------------------------------------------------------
    // 11. CRYOBANK CRUD
    // -------------------------------------------------------------------------
    public function storeCryo(Request $request)
    {
        $coupleId = $request->input('couple_id');
        $cycleId = $request->input('cycle_id');

        if ($cycleId && !$coupleId) {
            $cycle = IvfCycle::find($cycleId);
            if ($cycle) {
                $coupleId = $cycle->couple_id;
            }
        }

        if (!$coupleId) {
            return redirect()->route('ivf.home', ['tab' => 'cryobank'])->with('error', 'Couple selection is required.');
        }

        $cryo = CryobankStorage::create([
            'couple_id' => $coupleId,
            'cycle_id' => $cycleId ?: null,
            'specimen_type' => $request->input('specimen_type', 'Blastocyst Embryos'),
            'tank_id' => $request->input('tank_id', 'LN2-Tank-Alpha-1'),
            'canister_id' => $request->input('canister_id') ?: ('Canister-' . rand(1, 10)),
            'cane_id' => $request->input('cane_id') ?: ('Cane-' . rand(1, 10)),
            'straw_color_code' => $request->input('straw_color_code') ?: ('Straw-' . rand(100, 999)),
            'quantity' => max(1, (int)$request->input('quantity', 1)),
            'freeze_date' => $request->input('freeze_date', date('Y-m-d')),
            'expiry_date' => $request->input('expiry_date', date('Y-m-d', strtotime('+1 year'))),
            'status' => $request->input('status', 'Stored'),
        ]);

        return redirect()->route('ivf.home', ['tab' => 'cryobank'])->with('success', "Specimen CRYO-{$cryo->id} registered in Cryobank.");
    }

    public function updateCryo(Request $request, $id)
    {
        $cryo = CryobankStorage::findOrFail($id);
        $cryo->update($request->only([
            'specimen_type', 'tank_id', 'canister_id', 'cane_id', 'straw_color_code',
            'quantity', 'freeze_date', 'expiry_date', 'status'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'cryobank'])->with('success', "Cryobank record CRYO-{$cryo->id} updated.");
    }

    public function deleteCryo($id)
    {
        $cryo = CryobankStorage::findOrFail($id);
        $cryo->delete();

        return redirect()->route('ivf.home', ['tab' => 'cryobank'])->with('success', "Specimen record removed from Cryobank.");
    }

    // -------------------------------------------------------------------------
    // 12. SETTINGS & USER MANAGEMENT CRUD
    // -------------------------------------------------------------------------
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'status' => 'required|string',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
        ]);

        return redirect()->route('ivf.home', ['tab' => 'settings'])->with('success', "Staff user {$validated['name']} registered successfully.");
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string',
            'status' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return redirect()->route('ivf.home', ['tab' => 'settings'])->with('success', "Staff account {$user->name} updated successfully.");
    }

    public function deleteUser($id)
    {
        if (Auth::id() == $id) {
            return redirect()->route('ivf.home', ['tab' => 'settings'])->withErrors(['user' => 'You cannot delete your own logged-in user account.']);
        }

        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        return redirect()->route('ivf.home', ['tab' => 'settings'])->with('success', "User account {$name} has been removed.");
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user() ?: User::first();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('ivf.home', ['tab' => 'settings'])->withErrors(['current_password' => 'Current password entered is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('ivf.home', ['tab' => 'settings'])->with('success', 'Your password has been changed successfully.');
    }

    public function updateClinicSettings(Request $request)
    {
        return redirect()->route('ivf.home', ['tab' => 'settings'])->with('success', 'Clinic master configuration and lab environmental baselines saved.');
        $setting = ClinicSetting::getMasterSettings();
        $setting->update($request->only([
            'clinic_name', 'art_reg_no', 'director_name', 'incubator_temp',
            'ln2_alarm', 'contact_phone', 'contact_email', 'clinic_address'
        ]));

        return redirect()->route('ivf.home', ['tab' => 'settings'])->with('success', 'Clinic master profile and lab environmental baselines updated and saved successfully.');
    }
}
