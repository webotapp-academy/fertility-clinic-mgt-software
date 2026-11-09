<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Patient;
use App\Models\Couple;
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
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================================
        // 1. LEADS / INQUIRIES
        // =========================================================================
        Lead::create([
            'lead_code' => 'LED-2026-001',
            'wife_name' => 'Sophia Thorne',
            'husband_name' => 'Liam Thorne',
            'phone' => '+91 98201 23456',
            'email' => 'sophia.thorne@example.com',
            'infertility_years' => 4,
            'infertility_type' => 'Primary',
            'source' => 'Website Inquiry',
            'status' => 'Converted',
            'notes' => 'Referred by Dr. Montgomery. Seeking Antagonist ICSI consultation.',
        ]);

        Lead::create([
            'lead_code' => 'LED-2026-002',
            'wife_name' => 'Maya Patel',
            'husband_name' => 'Arjun Patel',
            'phone' => '+91 98202 34567',
            'email' => 'maya.patel@example.com',
            'infertility_years' => 3,
            'infertility_type' => 'Secondary',
            'source' => 'Doctor Referral',
            'status' => 'Converted',
            'notes' => 'History of previous cycle failure at outside clinic. Looking for second opinion & protocol optimization.',
        ]);

        Lead::create([
            'lead_code' => 'LED-2026-003',
            'wife_name' => 'Clara Bennett',
            'husband_name' => 'Thomas Bennett',
            'phone' => '+91 98203 45678',
            'email' => 'clara.bennett@example.com',
            'infertility_years' => 2,
            'infertility_type' => 'Primary',
            'source' => 'Walk-in',
            'status' => 'Consult Scheduled',
            'notes' => 'Baseline AMH test ordered. Scheduled for initial ultrasound.',
        ]);

        Lead::create([
            'lead_code' => 'LED-2026-004',
            'wife_name' => 'Elena Rostova',
            'husband_name' => 'Dmitri Rostov',
            'phone' => '+91 98204 56789',
            'email' => 'elena.rostova@example.com',
            'infertility_years' => 5,
            'infertility_type' => 'Primary',
            'source' => 'Helpline',
            'status' => 'Contacted',
            'notes' => 'Discussed donor oocyte possibilities. Awaiting follow-up semen analysis.',
        ]);

        // =========================================================================
        // JOURNEY 1: FULL SUCCESSFUL JOURNEY (ENQUIRY -> BABY -> VACCINATIONS)
        // =========================================================================
        $pWife1 = Patient::create([
            'mrn' => 'MRN-F-1001',
            'first_name' => 'Sophia',
            'last_name' => 'Thorne',
            'gender' => 'Female',
            'dob' => '1993-04-12',
            'phone' => '+91 98201 23456',
            'email' => 'sophia.thorne@example.com',
            'blood_group' => 'A Positive',
            'kyc_id_type' => 'Passport',
            'kyc_id_number' => 'P89234101',
        ]);

        $pHusb1 = Patient::create([
            'mrn' => 'MRN-M-1002',
            'first_name' => 'Liam',
            'last_name' => 'Thorne',
            'gender' => 'Male',
            'dob' => '1990-08-25',
            'phone' => '+91 98205 67890',
            'email' => 'liam.thorne@example.com',
            'blood_group' => 'O Positive',
            'kyc_id_type' => 'Passport',
            'kyc_id_number' => 'P89234102',
        ]);

        $cpl1 = Couple::create([
            'couple_code' => 'CPL-7001',
            'wife_id' => $pWife1->id,
            'husband_id' => $pHusb1->id,
            'marriage_years' => 4,
            'primary_doctor' => 'Dr. Elena Vance, MD (Reprod. Endo)',
            'counselor_name' => 'Hannah Sterling',
            'consent_status' => 'Verified',
            'status' => 'Delivered',
        ]);

        InfertilityWorkup::create([
            'couple_id' => $cpl1->id,
            'female_amh' => 2.85,
            'female_afc' => 16,
            'female_fsh' => 6.20,
            'female_tsh' => 1.85,
            'tubal_patency' => 'Both Patent (Bilateral free peritoneal spill)',
            'uterine_cavity_notes' => 'Normal cavity, regular contour, no polyps/fibroids.',
            'semen_volume_ml' => 3.2,
            'sperm_count_million' => 18.5,
            'sperm_motility_percent' => 35,
            'sperm_morphology_percent' => 3,
            'sperm_dfi_percent' => 14,
            'clinical_diagnosis' => 'Mild Teratozoospermia & Unexplained Subfertility',
            'doctor_recommendation' => 'Proceed with GnRH Antagonist Protocol with ICSI.',
        ]);

        $cycle1 = IvfCycle::create([
            'couple_id' => $cpl1->id,
            'cycle_number' => 1,
            'cycle_type' => 'Self-ICSI',
            'protocol_name' => 'Flexible GnRH Antagonist Protocol',
            'is_reattempt' => false,
            'start_date' => Carbon::now()->subMonths(10)->format('Y-m-d'),
            'trigger_datetime' => Carbon::now()->subMonths(10)->addDays(11)->setTime(22, 30),
            'trigger_drug' => 'Ovitrelle 250 mcg (Recombinant hCG)',
            'status' => 'Outcome_Positive',
            'cycle_notes' => 'Smooth ovarian response. 14 follicles stimulated uniformly.',
        ]);

        // Scans for Journey 1
        FollicularScan::create([
            'cycle_id' => $cycle1->id,
            'scan_date' => Carbon::now()->subMonths(10)->addDays(2)->format('Y-m-d'),
            'cycle_day' => 2,
            'right_ovary_follicles' => 'AFC: 8 follicles (6-8mm)',
            'left_ovary_follicles' => 'AFC: 8 follicles (5-7mm)',
            'endometrium_thickness_mm' => 3.4,
            'endometrium_pattern' => 'Trilaminar',
            'estradiol_e2_pg_ml' => 45.0,
            'progesterone_p4_ng_ml' => 0.4,
            'gonadotropin_dose_given' => 'Recombinant FSH 225 IU daily start',
            'notes' => 'Baseline scan clear. Stimulation initiated.',
        ]);

        FollicularScan::create([
            'cycle_id' => $cycle1->id,
            'scan_date' => Carbon::now()->subMonths(10)->addDays(7)->format('Y-m-d'),
            'cycle_day' => 7,
            'right_ovary_follicles' => '14mm, 13mm, 12mm, 11mm, 10mm',
            'left_ovary_follicles' => '15mm, 14mm, 13mm, 11mm, 10mm',
            'endometrium_thickness_mm' => 7.8,
            'endometrium_pattern' => 'Trilaminar',
            'estradiol_e2_pg_ml' => 680.0,
            'progesterone_p4_ng_ml' => 0.6,
            'gonadotropin_dose_given' => 'rFSH 225 IU + Cetrotide 0.25 mg added',
            'notes' => 'Lead follicle 15mm. Antagonist initiated today.',
        ]);

        FollicularScan::create([
            'cycle_id' => $cycle1->id,
            'scan_date' => Carbon::now()->subMonths(10)->addDays(11)->format('Y-m-d'),
            'cycle_day' => 11,
            'right_ovary_follicles' => '20mm, 19mm, 18mm, 17mm, 15mm, 14mm',
            'left_ovary_follicles' => '21mm, 19mm, 18mm, 17mm, 16mm, 14mm',
            'endometrium_thickness_mm' => 10.2,
            'endometrium_pattern' => 'Trilaminar',
            'estradiol_e2_pg_ml' => 2450.0,
            'progesterone_p4_ng_ml' => 0.9,
            'gonadotropin_dose_given' => 'Trigger: Ovitrelle 250 mcg at 22:30 exact',
            'notes' => 'Criteria met. OPU scheduled strictly 35.5 hrs post trigger.',
        ]);

        // OPU & Embryology Lab
        OpuAndLabRecord::create([
            'cycle_id' => $cycle1->id,
            'opu_date' => Carbon::now()->subMonths(10)->addDays(13)->format('Y-m-d'),
            'oocytes_retrieved' => 14,
            'mii_mature_count' => 12,
            'mi_count' => 1,
            'gv_count' => 1,
            'insemination_method' => 'ICSI',
            'semen_prep_method' => 'Density Gradient Centrifugation',
            'post_wash_motile_count_million' => 12.4,
            'fertilized_2pn_count' => 10,
            'day3_cleaved_count' => 9,
            'day5_blast_count' => 5,
            'blastocyst_grades' => 'Day 5: 4AA (selected for Fresh ET), 4AB (vitrified), 4BB (vitrified), 3BB (vitrified), 3BB (vitrified)',
            'embryologist_name' => 'Dr. Sarah Chen, Senior Embryologist',
        ]);

        // Cryo Bio-bank record for spare embryos
        CryobankStorage::create([
            'couple_id' => $cpl1->id,
            'cycle_id' => $cycle1->id,
            'specimen_type' => 'Blastocyst Embryos',
            'tank_id' => 'LN2-Tank-Alpha-1',
            'canister_id' => 'Canister-02',
            'cane_id' => 'Cane-08',
            'straw_color_code' => 'White-Straw-4AB/4BB',
            'quantity' => 4,
            'freeze_date' => Carbon::now()->subMonths(10)->addDays(18)->format('Y-m-d'),
            'expiry_date' => Carbon::now()->subMonths(10)->addDays(18)->addYear()->format('Y-m-d'),
            'status' => 'Stored',
        ]);

        // Fresh Embryo Transfer
        EmbryoTransfer::create([
            'cycle_id' => $cycle1->id,
            'transfer_type' => 'Fresh ET',
            'transfer_date' => Carbon::now()->subMonths(10)->addDays(18)->format('Y-m-d'),
            'embryos_transferred_count' => 1,
            'embryos_selected' => 'Single Day-5 Blastocyst (Grade 4AA - Fully expanded)',
            'catheter_type' => 'Cook Sydney Soft-Pass Echogenic',
            'ease_of_transfer' => 'Easy / Smooth, no blood on catheter tip',
            'ultrasound_guided' => true,
            'doctor_name' => 'Dr. Elena Vance, MD',
        ]);

        // Outcome (Positive)
        CycleOutcome::create([
            'cycle_id' => $cycle1->id,
            'betahcg_day14' => 485.50,
            'betahcg_day16' => 1120.00,
            'result_status' => 'Positive',
            'gestational_sacs' => 1,
            'fetal_heartbeat' => true,
            'is_failure' => false,
            'failure_stage' => null,
            'root_cause_analysis' => null,
            'reattempt_strategy' => null,
        ]);

        // Antenatal Care (ANC)
        $anc1 = AntenatalRecord::create([
            'couple_id' => $cpl1->id,
            'cycle_id' => $cycle1->id,
            'edd' => Carbon::now()->subDays(15)->format('Y-m-d'),
            'gravida' => 1,
            'para' => 1,
            'risk_classification' => 'Standard IVF Primigravida',
            'nt_scan_result' => 'NT 1.3mm, Nasal bone present. Normal low risk.',
            'anomaly_scan_result' => 'Targeted Level II Scan: Normal fetal anatomy, active movements.',
            'pregnancy_status' => 'Delivered',
            'obstetrician_name' => 'Dr. Rachel Morgan, OB/GYN',
        ]);

        // Delivery
        $deliv1 = DeliveryRecord::create([
            'antenatal_record_id' => $anc1->id,
            'delivery_datetime' => Carbon::now()->subDays(14)->setTime(8, 45),
            'mode_of_delivery' => 'Elective LSCS (Cesarean)',
            'indication_notes' => 'Precious IVF pregnancy at 38 weeks 4 days. Maternal choice for elective LSCS.',
            'maternal_complications' => 'None. Blood loss minimal (350 mL).',
            'delivering_surgeon' => 'Dr. Rachel Morgan, OB/GYN',
        ]);

        // Baby Record
        $baby1 = BabyRecord::create([
            'delivery_record_id' => $deliv1->id,
            'baby_code' => 'BBY-7001-A',
            'baby_name' => 'Aria Thorne',
            'gender' => 'Female',
            'birth_weight_grams' => 3240,
            'apgar_1min' => 9,
            'apgar_5min' => 10,
            'pediatrician_name' => 'Dr. David Kim, MD (Pediatrics)',
            'discharge_notes' => 'Cried immediately at birth. Vitals stable. Good suck reflex. Discharged in healthy condition.',
        ]);

        // Vaccination Schedule (From Birth to 15 Months)
        $birthDate = Carbon::now()->subDays(14);
        
        // At Birth (Administered)
        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => 'BCG (Tuberculosis)',
            'milestone_stage' => 'At Birth',
            'due_date' => $birthDate->format('Y-m-d'),
            'administered_date' => $birthDate->format('Y-m-d'),
            'status' => 'Administered',
            'batch_number' => 'BCG-2026-X99',
            'administered_by' => 'Nurse Claire, RN',
        ]);

        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => 'OPV-0 (Oral Polio)',
            'milestone_stage' => 'At Birth',
            'due_date' => $birthDate->format('Y-m-d'),
            'administered_date' => $birthDate->format('Y-m-d'),
            'status' => 'Administered',
            'batch_number' => 'OPV-8812',
            'administered_by' => 'Nurse Claire, RN',
        ]);

        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => 'Hepatitis B (Birth Dose)',
            'milestone_stage' => 'At Birth',
            'due_date' => $birthDate->format('Y-m-d'),
            'administered_date' => $birthDate->format('Y-m-d'),
            'status' => 'Administered',
            'batch_number' => 'HEPB-4401',
            'administered_by' => 'Nurse Claire, RN',
        ]);

        // 6 Weeks (Upcoming Scheduled)
        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => 'DTwP / DTaP - 1, IPV-1, Hib-1, HepB-1 (6-in-1 Hexavalent)',
            'milestone_stage' => '6 Weeks',
            'due_date' => $birthDate->copy()->addWeeks(6)->format('Y-m-d'),
            'administered_date' => null,
            'status' => 'Scheduled',
            'batch_number' => null,
            'administered_by' => null,
        ]);

        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => 'Rotavirus Vaccine - 1',
            'milestone_stage' => '6 Weeks',
            'due_date' => $birthDate->copy()->addWeeks(6)->format('Y-m-d'),
            'administered_date' => null,
            'status' => 'Scheduled',
            'batch_number' => null,
            'administered_by' => null,
        ]);

        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => 'PCV-1 (Pneumococcal Conjugate)',
            'milestone_stage' => '6 Weeks',
            'due_date' => $birthDate->copy()->addWeeks(6)->format('Y-m-d'),
            'administered_date' => null,
            'status' => 'Scheduled',
            'batch_number' => null,
            'administered_by' => null,
        ]);

        // 10 Weeks
        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => '6-in-1 Hexavalent - 2 & Rotavirus - 2',
            'milestone_stage' => '10 Weeks',
            'due_date' => $birthDate->copy()->addWeeks(10)->format('Y-m-d'),
            'administered_date' => null,
            'status' => 'Scheduled',
            'batch_number' => null,
            'administered_by' => null,
        ]);

        // 14 Weeks
        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => '6-in-1 Hexavalent - 3, Rotavirus - 3 & PCV - 2',
            'milestone_stage' => '14 Weeks',
            'due_date' => $birthDate->copy()->addWeeks(14)->format('Y-m-d'),
            'administered_date' => null,
            'status' => 'Scheduled',
            'batch_number' => null,
            'administered_by' => null,
        ]);

        // 6 Months
        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => 'Influenza Vaccine (Flu) - Dose 1 & Typhoid Conjugate',
            'milestone_stage' => '6 Months',
            'due_date' => $birthDate->copy()->addMonths(6)->format('Y-m-d'),
            'administered_date' => null,
            'status' => 'Scheduled',
            'batch_number' => null,
            'administered_by' => null,
        ]);

        // 9 Months
        VaccinationSchedule::create([
            'baby_record_id' => $baby1->id,
            'vaccine_name' => 'MMR - 1 (Measles, Mumps, Rubella)',
            'milestone_stage' => '9 Months',
            'due_date' => $birthDate->copy()->addMonths(9)->format('Y-m-d'),
            'administered_date' => null,
            'status' => 'Scheduled',
            'batch_number' => null,
            'administered_by' => null,
        ]);


        // =========================================================================
        // JOURNEY 2: FAILED ATTEMPT -> ROOT CAUSE ANALYSIS -> RE-ATTEMPT (CYCLE 2)
        // =========================================================================
        $pWife2 = Patient::create([
            'mrn' => 'MRN-F-1003',
            'first_name' => 'Maya',
            'last_name' => 'Patel',
            'gender' => 'Female',
            'dob' => '1989-11-04',
            'phone' => '+91 98202 34567',
            'email' => 'maya.patel@example.com',
            'blood_group' => 'B Positive',
            'kyc_id_type' => 'Driver License',
            'kyc_id_number' => 'DL-9082341',
        ]);

        $pHusb2 = Patient::create([
            'mrn' => 'MRN-M-1004',
            'first_name' => 'Arjun',
            'last_name' => 'Patel',
            'gender' => 'Male',
            'dob' => '1987-03-15',
            'phone' => '+91 98206 78901',
            'email' => 'arjun.patel@example.com',
            'blood_group' => 'O Positive',
            'kyc_id_type' => 'Driver License',
            'kyc_id_number' => 'DL-9082342',
        ]);

        $cpl2 = Couple::create([
            'couple_code' => 'CPL-7002',
            'wife_id' => $pWife2->id,
            'husband_id' => $pHusb2->id,
            'marriage_years' => 6,
            'primary_doctor' => 'Dr. Elena Vance, MD (Reprod. Endo)',
            'counselor_name' => 'Hannah Sterling',
            'consent_status' => 'Verified',
            'status' => 'In Treatment',
        ]);

        InfertilityWorkup::create([
            'couple_id' => $cpl2->id,
            'female_amh' => 0.95,
            'female_afc' => 6,
            'female_fsh' => 11.20,
            'female_tsh' => 2.10,
            'tubal_patency' => 'Both Patent',
            'uterine_cavity_notes' => 'Minimal adenomyosis on posterior wall, otherwise normal.',
            'semen_volume_ml' => 2.0,
            'sperm_count_million' => 8.5,
            'sperm_motility_percent' => 22,
            'sperm_morphology_percent' => 2,
            'sperm_dfi_percent' => 38, // High DFI
            'clinical_diagnosis' => 'Poor Ovarian Reserve (POR) & Elevated Sperm DFI (38%)',
            'doctor_recommendation' => 'Initial Attempt: Standard Antagonist. If yield compromised, evaluate for Microdose Flare + Microfluidic sorting.',
        ]);

        // Cycle 1: Failed Cycle
        $cpl2Cycle1 = IvfCycle::create([
            'couple_id' => $cpl2->id,
            'cycle_number' => 1,
            'cycle_type' => 'Self-ICSI',
            'protocol_name' => 'Standard Antagonist Protocol',
            'is_reattempt' => false,
            'start_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
            'trigger_datetime' => Carbon::now()->subMonths(4)->addDays(10)->setTime(21, 00),
            'trigger_drug' => 'Ovitrelle 250 mcg',
            'status' => 'Outcome_Failed',
            'cycle_notes' => 'Asynchronous growth observed during stimulation.',
        ]);

        OpuAndLabRecord::create([
            'cycle_id' => $cpl2Cycle1->id,
            'opu_date' => Carbon::now()->subMonths(4)->addDays(12)->format('Y-m-d'),
            'oocytes_retrieved' => 4,
            'mii_mature_count' => 2,
            'mi_count' => 1,
            'gv_count' => 1,
            'insemination_method' => 'ICSI',
            'semen_prep_method' => 'Standard Swim-up',
            'post_wash_motile_count_million' => 3.2,
            'fertilized_2pn_count' => 1,
            'day3_cleaved_count' => 1,
            'day5_blast_count' => 0,
            'blastocyst_grades' => 'Day 3: 4-cell with 35% fragmentation. Arrested on Day 4. No blastocyst.',
            'embryologist_name' => 'Dr. Sarah Chen, Senior Embryologist',
        ]);

        CycleOutcome::create([
            'cycle_id' => $cpl2Cycle1->id,
            'betahcg_day14' => 1.20,
            'betahcg_day16' => null,
            'result_status' => 'Negative',
            'gestational_sacs' => 0,
            'fetal_heartbeat' => false,
            'is_failure' => true,
            'failure_stage' => 'Blastulation Arrest & High Sperm DFI',
            'root_cause_analysis' => 'Root Cause Analysis: Severe embryo arrest prior to Day 5. Attributed to combination of advanced paternal factor (elevated DFI 38%) and poor oocyte yield (4 oocytes, 2 MII).',
            'reattempt_strategy' => 'Re-attempt Strategy Formulated: (1) Switch to Microdose GnRH Agonist Flare Protocol with Growth Hormone (Omnitrope 1 IU/day) priming. (2) Replace standard semen swim-up with Zymot Microfluidic sperm sorting to filter low-DFI sperm. (3) Utilize Calcium Ionophore A23187 oocyte activation to overcome cleavage arrest.',
        ]);

        // Cycle 2: The Re-Attempt! (Linked to Cycle 1)
        $cpl2Cycle2 = IvfCycle::create([
            'couple_id' => $cpl2->id,
            'cycle_number' => 2,
            'cycle_type' => 'Self-ICSI (Re-attempt)',
            'protocol_name' => 'Microdose Agonist Flare + Growth Hormone Priming',
            'is_reattempt' => true,
            'previous_cycle_id' => $cpl2Cycle1->id,
            'start_date' => Carbon::now()->subWeeks(3)->format('Y-m-d'),
            'trigger_datetime' => Carbon::now()->subDays(6)->setTime(22, 00),
            'trigger_drug' => 'Dual Trigger (Decapeptyl 0.2mg + Ovitrelle 250mcg)',
            'status' => 'OPU_Completed',
            'cycle_notes' => 'Significant improvement: Synchronized cohort of 8 follicles after Growth Hormone priming.',
        ]);

        FollicularScan::create([
            'cycle_id' => $cpl2Cycle2->id,
            'scan_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
            'cycle_day' => 10,
            'right_ovary_follicles' => '19mm, 18mm, 17mm, 15mm',
            'left_ovary_follicles' => '19mm, 18mm, 16mm, 14mm',
            'endometrium_thickness_mm' => 9.4,
            'endometrium_pattern' => 'Trilaminar',
            'estradiol_e2_pg_ml' => 1890.0,
            'progesterone_p4_ng_ml' => 0.8,
            'gonadotropin_dose_given' => 'Dual Trigger at 22:00',
            'notes' => '8 lead follicles ready. OPU scheduled.',
        ]);

        OpuAndLabRecord::create([
            'cycle_id' => $cpl2Cycle2->id,
            'opu_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'oocytes_retrieved' => 8,
            'mii_mature_count' => 7,
            'mi_count' => 1,
            'gv_count' => 0,
            'insemination_method' => 'ICSI with Zymōt Sorting & Calcium Ionophore Activation',
            'semen_prep_method' => 'Zymōt Microfluidic Chip (DFI reduction)',
            'post_wash_motile_count_million' => 6.8,
            'fertilized_2pn_count' => 6,
            'day3_cleaved_count' => 5,
            'day5_blast_count' => 3,
            'blastocyst_grades' => 'Day 5: 4AA (Grade 1), 4AB (Grade 1), 3BB (Grade 2). Successfully vitrified for FET.',
            'embryologist_name' => 'Dr. Sarah Chen, Senior Embryologist',
        ]);

        CryobankStorage::create([
            'couple_id' => $cpl2->id,
            'cycle_id' => $cpl2Cycle2->id,
            'specimen_type' => 'Blastocyst Embryos',
            'tank_id' => 'LN2-Tank-Alpha-1',
            'canister_id' => 'Canister-04',
            'cane_id' => 'Cane-02',
            'straw_color_code' => 'Black-Straw-4AA/4AB/3BB',
            'quantity' => 3,
            'freeze_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'expiry_date' => Carbon::now()->addYear()->format('Y-m-d'),
            'status' => 'Stored',
        ]);

        // =========================================================================
        // JOURNEY 3: ACTIVE IN-TREATMENT CYCLE (STIMULATION MONITORING TODAY)
        // =========================================================================
        $pWife3 = Patient::create([
            'mrn' => 'MRN-F-1005',
            'first_name' => 'Clara',
            'last_name' => 'Bennett',
            'gender' => 'Female',
            'dob' => '1995-07-19',
            'phone' => '+91 98203 45678',
            'email' => 'clara.bennett@example.com',
            'blood_group' => 'O Negative',
            'kyc_id_type' => 'Passport',
            'kyc_id_number' => 'P1098234',
        ]);

        $pHusb3 = Patient::create([
            'mrn' => 'MRN-M-1006',
            'first_name' => 'Thomas',
            'last_name' => 'Bennett',
            'gender' => 'Male',
            'dob' => '1993-01-30',
            'phone' => '+91 98207 89012',
            'email' => 'thomas.bennett@example.com',
            'blood_group' => 'A Positive',
            'kyc_id_type' => 'Passport',
            'kyc_id_number' => 'P1098235',
        ]);

        $cpl3 = Couple::create([
            'couple_code' => 'CPL-7003',
            'wife_id' => $pWife3->id,
            'husband_id' => $pHusb3->id,
            'marriage_years' => 2,
            'primary_doctor' => 'Dr. Elena Vance, MD (Reprod. Endo)',
            'counselor_name' => 'Hannah Sterling',
            'consent_status' => 'Verified',
            'status' => 'In Treatment',
        ]);

        InfertilityWorkup::create([
            'couple_id' => $cpl3->id,
            'female_amh' => 3.40,
            'female_afc' => 20,
            'female_fsh' => 5.80,
            'female_tsh' => 1.50,
            'tubal_patency' => 'Both Patent',
            'uterine_cavity_notes' => 'Normal anteverted uterus.',
            'semen_volume_ml' => 3.5,
            'sperm_count_million' => 45.0,
            'sperm_motility_percent' => 55,
            'sperm_morphology_percent' => 5,
            'sperm_dfi_percent' => 10,
            'clinical_diagnosis' => 'High Reserve / PCOS Tendency & Mild Ovulatory Factor',
            'doctor_recommendation' => 'Antagonist protocol with moderate gonadotropin dose to prevent OHSS. Agonist trigger plan.',
        ]);

        $cpl3Cycle = IvfCycle::create([
            'couple_id' => $cpl3->id,
            'cycle_number' => 1,
            'cycle_type' => 'Self-ICSI',
            'protocol_name' => 'Antagonist Protocol (Freeze-all OHSS Prevention)',
            'is_reattempt' => false,
            'start_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'trigger_datetime' => null,
            'trigger_drug' => 'GnRH Agonist Trigger (Decapeptyl 0.2mg planned)',
            'status' => 'Stimulation',
            'cycle_notes' => 'Currently on Day 6 stimulation. Daily monitoring active.',
        ]);

        FollicularScan::create([
            'cycle_id' => $cpl3Cycle->id,
            'scan_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'cycle_day' => 1,
            'right_ovary_follicles' => 'AFC: 10 (4-6mm)',
            'left_ovary_follicles' => 'AFC: 10 (4-6mm)',
            'endometrium_thickness_mm' => 3.1,
            'endometrium_pattern' => 'Trilaminar',
            'estradiol_e2_pg_ml' => 38.0,
            'progesterone_p4_ng_ml' => 0.3,
            'gonadotropin_dose_given' => 'rFSH 175 IU',
            'notes' => 'Stimulation started.',
        ]);

        FollicularScan::create([
            'cycle_id' => $cpl3Cycle->id,
            'scan_date' => Carbon::now()->format('Y-m-d'),
            'cycle_day' => 6,
            'right_ovary_follicles' => '14mm, 13mm, 12mm, 12mm, 11mm, 10mm',
            'left_ovary_follicles' => '13mm, 13mm, 12mm, 11mm, 10mm, 10mm',
            'endometrium_thickness_mm' => 6.9,
            'endometrium_pattern' => 'Trilaminar',
            'estradiol_e2_pg_ml' => 520.0,
            'progesterone_p4_ng_ml' => 0.5,
            'gonadotropin_dose_given' => 'rFSH 175 IU + Cetrotide 0.25mg (Day 1)',
            'notes' => 'Good symmetrical recruitment. Started antagonist Cetrotide today.',
        ]);
    }
}
