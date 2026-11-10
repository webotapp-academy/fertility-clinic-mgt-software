@extends('ivf.layout')

@section('content')

    <!-- ===================================================================== -->
    <!-- TAB 1: OVERVIEW & CLINICAL STATS -->
    <!-- ===================================================================== -->
    @if($activeTab === 'overview')
        <!-- TOP KPI METRIC STRIP (SHOWN ONLY ON OVERVIEW DASHBOARD) -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-label">Enquiries & Leads</div>
                <div class="metric-value">{{ $metrics['total_leads'] }}</div>
                <div class="metric-sub">Active CRM pipeline</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Registered Couples</div>
                <div class="metric-value">{{ $metrics['active_couples'] }}</div>
                <div class="metric-sub">Verified legal consents</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Active Stimulation</div>
                <div class="metric-value">{{ $metrics['cycles_underway'] }}</div>
                <div class="metric-sub">Daily ultrasound logs</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Re-attempts Managed</div>
                <div class="metric-value">{{ $metrics['reattempt_cycles'] }}</div>
                <div class="metric-sub">Post-failure optimization</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Cryobank Straws</div>
                <div class="metric-value">{{ $metrics['cryo_specimens'] }}</div>
                <div class="metric-sub">In Liquid N2 Tanks</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Delivered Babies</div>
                <div class="metric-value">{{ $metrics['live_births'] }}</div>
                <div class="metric-sub">Live birth graduations</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Vaccines Tracked</div>
                <div class="metric-value">{{ $metrics['vaccines_due'] }}</div>
                <div class="metric-sub">Scheduled milestones</div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Clinical Performance & Quality Benchmarks</div>
                    <div class="panel-desc">Real-time success rates, embryology lab benchmarks, and clinical outcome metrics</div>
                </div>
            </div>

            <div style="padding: 1.5rem; background: #ffffff;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1px; background: var(--border-strong); border: 1px solid var(--border-strong);">
                    <div class="metric-card">
                        <div class="metric-label">Clinical Pregnancy Rate (CPR)</div>
                        <div class="metric-value">66.7%</div>
                        <div class="metric-sub">Confirmed gestational sac</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">2PN Fertilization Rate</div>
                        <div class="metric-value">84.2%</div>
                        <div class="metric-sub">Target benchmark: > 75%</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Day 5 Blastulation Rate</div>
                        <div class="metric-value">61.5%</div>
                        <div class="metric-sub">Gardner Grade 1 & 2 blasts</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Single Embryo Transfer (SET)</div>
                        <div class="metric-value">100.0%</div>
                        <div class="metric-sub">Zero multiple order risk</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Embryo Cryo Survival</div>
                        <div class="metric-value">100.0%</div>
                        <div class="metric-sub">Post-thaw recovery rate</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Live Birth Take-Home Rate</div>
                        <div class="metric-value">100.0%</div>
                        <div class="metric-sub">Term healthy deliveries</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QUICK SUMMARY TABLES ON OVERVIEW -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Active Treatment Cycles</div>
                    <a href="{{ route('ivf.home', ['tab' => 'cycles']) }}" class="btn btn-outline btn-sm">Manage Cycles &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="clinical-table">
                        <thead>
                            <tr>
                                <th>Cycle #</th>
                                <th>Patient / Couple</th>
                                <th>Protocol</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cycles->take(4) as $cycle)
                                <tr>
                                    <td class="mono font-bold">CYC-{{ $cycle->id }}</td>
                                    <td>
                                        <strong>{{ $cycle->couple->wife->first_name }} {{ $cycle->couple->wife->last_name }}</strong>
                                        <div class="mono text-muted" style="font-size: 10.5px;">{{ $cycle->couple->couple_code }}</div>
                                    </td>
                                    <td>{{ $cycle->protocol_name }}</td>
                                    <td>
                                        <span class="tag {{ $cycle->status === 'Stimulation' ? 'tag-black' : 'tag-outline' }}">
                                            {{ str_replace('_', ' ', $cycle->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Upcoming Pediatric Vaccinations</div>
                    <a href="{{ route('ivf.home', ['tab' => 'pediatrics']) }}" class="btn btn-outline btn-sm">Manage Vaccines &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="clinical-table">
                        <thead>
                            <tr>
                                <th>Baby</th>
                                <th>Vaccine</th>
                                <th>Milestone</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentVaccines = \App\Models\VaccinationSchedule::with('baby.deliveryRecord.antenatalRecord.couple.wife')->take(4)->get();
                            @endphp
                            @foreach($recentVaccines as $vac)
                                <tr>
                                    <td>
                                        <strong>{{ $vac->baby->baby_name }}</strong>
                                        <div class="mono text-muted" style="font-size: 10.5px;">{{ $vac->baby->baby_code }}</div>
                                    </td>
                                    <td>{{ $vac->vaccine_name }}</td>
                                    <td class="mono">{{ $vac->milestone_stage }}</td>
                                    <td>
                                        <span class="tag {{ $vac->status === 'Administered' ? 'tag-black' : 'tag-outline' }}">
                                            {{ $vac->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 2: ENQUIRIES & LEADS CRM -->
    <!-- ===================================================================== -->
    @if($activeTab === 'leads')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Lead Management, Enquiries & CRM</div>
                    <div class="panel-desc">Capture, edit, update status, and manage all patient enquiries</div>
                </div>
                <div class="panel-actions">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newLeadModal')">+ Add New Enquiry</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>Lead Code</th>
                            <th>Wife Name</th>
                            <th>Husband Name</th>
                            <th>Contact</th>
                            <th>Infertility</th>
                            <th>Channel</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leads as $lead)
                            <tr>
                                <td class="mono font-bold">{{ $lead->lead_code }}</td>
                                <td><strong>{{ $lead->wife_name }}</strong></td>
                                <td>{{ $lead->husband_name ?? '—' }}</td>
                                <td class="mono">
                                    {{ $lead->phone }}
                                    <div class="text-muted" style="font-size: 10.5px;">{{ $lead->email }}</div>
                                </td>
                                <td>{{ $lead->infertility_years }} yrs ({{ $lead->infertility_type }})</td>
                                <td>{{ $lead->source }}</td>
                                <td>
                                    <span class="tag {{ $lead->status === 'Converted' ? 'tag-black' : 'tag-outline' }}">
                                        {{ $lead->status }}
                                    </span>
                                </td>
                                <td style="max-width: 220px;">{{ $lead->notes }}</td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editLeadModal_{{ $lead->id }}')">Edit</button>
                                    <form action="{{ route('ivf.leads.delete', $lead->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove lead {{ $lead->lead_code }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm" style="color: #000000;">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- EDIT LEAD MODAL -->
                            <div class="modal-overlay" id="editLeadModal_{{ $lead->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Enquiry: {{ $lead->lead_code }}</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editLeadModal_{{ $lead->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.leads.update', $lead->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Wife Name *</label>
                                                    <input type="text" name="wife_name" class="form-control" value="{{ $lead->wife_name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Husband Name</label>
                                                    <input type="text" name="husband_name" class="form-control" value="{{ $lead->husband_name }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Mobile Number *</label>
                                                    <input type="tel" name="phone" class="form-control" value="{{ $lead->phone }}" placeholder="+91 98765 43210" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Email Address</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $lead->email }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Infertility Duration (Yrs)</label>
                                                    <input type="number" name="infertility_years" class="form-control" value="{{ $lead->infertility_years }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Infertility Classification</label>
                                                    <select name="infertility_type" class="form-control">
                                                        <option value="Primary" {{ $lead->infertility_type === 'Primary' ? 'selected' : '' }}>Primary</option>
                                                        <option value="Secondary" {{ $lead->infertility_type === 'Secondary' ? 'selected' : '' }}>Secondary</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Acquisition Channel</label>
                                                    <select name="source" class="form-control">
                                                        <option value="Website Inquiry" {{ $lead->source === 'Website Inquiry' ? 'selected' : '' }}>Website Inquiry</option>
                                                        <option value="Doctor Referral" {{ $lead->source === 'Doctor Referral' ? 'selected' : '' }}>Doctor Referral</option>
                                                        <option value="Walk-in" {{ $lead->source === 'Walk-in' ? 'selected' : '' }}>Walk-in Reception</option>
                                                        <option value="Helpline" {{ $lead->source === 'Helpline' ? 'selected' : '' }}>Helpline</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Lead Status *</label>
                                                    <select name="status" class="form-control">
                                                        <option value="New" {{ $lead->status === 'New' ? 'selected' : '' }}>New</option>
                                                        <option value="Contacted" {{ $lead->status === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                                        <option value="Consult Scheduled" {{ $lead->status === 'Consult Scheduled' ? 'selected' : '' }}>Consult Scheduled</option>
                                                        <option value="Converted" {{ $lead->status === 'Converted' ? 'selected' : '' }}>Converted (Registered)</option>
                                                        <option value="Dropped" {{ $lead->status === 'Dropped' ? 'selected' : '' }}>Dropped</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" style="grid-column: 1 / -1;">
                                                    <label class="form-label">Clinical / Counseling Notes</label>
                                                    <textarea name="notes" class="form-control">{{ $lead->notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editLeadModal_{{ $lead->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- NEW LEAD MODAL -->
        <div class="modal-overlay" id="newLeadModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Register New Patient Enquiry</div>
                    <button type="button" class="modal-close" onclick="closeModal('newLeadModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.leads.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group">
                                <label class="form-label">Wife / Female Partner Name *</label>
                                <input type="text" name="wife_name" class="form-control" placeholder="e.g. Rachel Adams" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Husband / Male Partner Name</label>
                                <input type="text" name="husband_name" class="form-control" placeholder="e.g. Robert Adams">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mobile Number *</label>
                                <input type="tel" name="phone" class="form-control" value="+91 " placeholder="+91 98765 43210" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="rachel@example.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Infertility Duration (Years)</label>
                                <input type="number" name="infertility_years" class="form-control" value="2" min="0" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Infertility Classification</label>
                                <select name="infertility_type" class="form-control">
                                    <option value="Primary">Primary Infertility</option>
                                    <option value="Secondary">Secondary Infertility</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Acquisition Channel</label>
                                <select name="source" class="form-control">
                                    <option value="Website Inquiry">Website Inquiry</option>
                                    <option value="Doctor Referral">Doctor Referral</option>
                                    <option value="Walk-in">Walk-in Reception</option>
                                    <option value="Helpline">Helpline</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Initial Status</label>
                                <select name="status" class="form-control">
                                    <option value="New">New</option>
                                    <option value="Contacted">Contacted</option>
                                    <option value="Consult Scheduled">Consult Scheduled</option>
                                </select>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Initial Counseling Notes & Clinical Remarks</label>
                                <textarea name="notes" class="form-control" placeholder="Prior treatments, medical history, specific concerns..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newLeadModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">Create Lead</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 3: COUPLES & INFERTILITY WORKUP -->
    <!-- ===================================================================== -->
    @if($activeTab === 'couples')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Couple Records & Pre-IVF Diagnostic Workup</div>
                    <div class="panel-desc">Biometrics, KYC identity, and editable diagnostic parameters (AMH, AFC, Kruger sperm, DFI)</div>
                </div>
                <div class="panel-actions">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newCoupleModal')">+ Register New Couple & Workup</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>Couple Code</th>
                            <th>Wife (Female Partner)</th>
                            <th>Husband (Male Partner)</th>
                            <th>Marriage</th>
                            <th>Ovarian Reserve</th>
                            <th>Semen Analysis (Kruger / DFI)</th>
                            <th>Clinical Diagnosis</th>
                            <th>Consent</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($couples as $cpl)
                            <tr>
                                <td class="mono font-bold">{{ $cpl->couple_code }}</td>
                                <td>
                                    <strong>{{ $cpl->wife->first_name }} {{ $cpl->wife->last_name }}</strong>
                                    <div class="mono text-muted" style="font-size: 10.5px;">MRN: {{ $cpl->wife->mrn }} ({{ $cpl->wife->blood_group }})</div>
                                </td>
                                <td>
                                    @if($cpl->husband)
                                        <strong>{{ $cpl->husband->first_name }} {{ $cpl->husband->last_name }}</strong>
                                        <div class="mono text-muted" style="font-size: 10.5px;">MRN: {{ $cpl->husband->mrn }} ({{ $cpl->husband->blood_group }})</div>
                                    @else
                                        <span class="text-muted">Donor Sperm Protocol</span>
                                    @endif
                                </td>
                                <td class="mono">{{ $cpl->marriage_years }} Years</td>
                                <td>
                                    @if($cpl->workup)
                                        <div class="mono"><strong>AMH:</strong> {{ $cpl->workup->female_amh }} ng/mL</div>
                                        <div class="mono text-muted" style="font-size: 10.5px;">AFC: {{ $cpl->workup->female_afc }} | FSH: {{ $cpl->workup->female_fsh }}</div>
                                    @else
                                        <span class="text-muted">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($cpl->workup)
                                        <div class="mono"><strong>Count:</strong> {{ $cpl->workup->sperm_count_million }}M/mL ({{ $cpl->workup->sperm_motility_percent }}% Motile)</div>
                                        <div class="mono text-muted" style="font-size: 10.5px;">Morph: {{ $cpl->workup->sperm_morphology_percent }}% | <strong>DFI:</strong> {{ $cpl->workup->sperm_dfi_percent }}%</div>
                                    @else
                                        <span class="text-muted">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $cpl->workup->clinical_diagnosis ?? 'Under Investigation' }}</strong>
                                    <div class="text-muted" style="font-size: 10.5px;">{{ $cpl->primary_doctor }}</div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('consentModal_{{ $cpl->id }}')" style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 8px; font-size: 11px;" title="View legal ART & ICMR informed consent document">
                                        <span class="tag tag-black" style="font-size: 9.5px; padding: 1px 5px;">{{ $cpl->consent_status }}</span>
                                        <span style="text-decoration: underline; font-weight: 600;">View Consent &rarr;</span>
                                    </button>
                                </td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('startCycleModal_{{ $cpl->id }}')">+ Start Cycle</button>
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editCoupleModal_{{ $cpl->id }}')">Edit Profile & Lab</button>
                                    <form action="{{ route('ivf.couples.delete', $cpl->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove couple {{ $cpl->couple_code }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- LEGAL INFORMED CONSENT MODAL -->
                            <div class="modal-overlay" id="consentModal_{{ $cpl->id }}">
                                <div class="modal-dialog" style="max-width: 760px;">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title">National ART Act Legal Informed Consent Document</div>
                                            <div class="text-muted mono" style="font-size: 10.5px;">COUPLE CODE: {{ $cpl->couple_code }} | FORM ART-04 (REGULATORY COMPLIANT)</div>
                                        </div>
                                        <button type="button" class="modal-close" onclick="closeModal('consentModal_{{ $cpl->id }}')">&times;</button>
                                    </div>
                                    <div class="modal-body" style="background: #ffffff; color: #000000; padding: 1.5rem; line-height: 1.6;">
                                        <!-- Header Block -->
                                        <div style="border: 2px solid #000000; padding: 1rem; margin-bottom: 1.25rem;">
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #000000; padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                                                <div>
                                                    <div style="font-weight: 800; font-size: 14.5px; text-transform: uppercase;">{{ $clinicSetting->clinic_name ?? 'AURA Reproductive Medicine Centre' }}</div>
                                                    <div class="mono" style="font-size: 10.5px;">ART Reg. No.: {{ $clinicSetting->art_reg_no ?? 'ART-IND-2026-0894' }}</div>
                                                    <div class="mono text-muted" style="font-size: 10px;">Medical Director: {{ $clinicSetting->director_name ?? 'Dr. Elena Vance, MD' }}</div>
                                                </div>
                                                <div style="text-align: right;">
                                                    <span class="tag tag-black" style="font-size: 10px;">STATUTORY RECORD</span>
                                                    <div class="mono" style="font-size: 11px; margin-top: 4px;">Consent Status: <strong>{{ $cpl->consent_status }}</strong></div>
                                                </div>
                                            </div>

                                            <!-- Couple Demographics -->
                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 11.5px; background: #fafafa; border: 1px solid #000000; padding: 10px;">
                                                <div>
                                                    <strong>Female Patient (Wife):</strong> {{ $cpl->wife->first_name }} {{ $cpl->wife->last_name }}<br>
                                                    <span class="mono text-muted">MRN: {{ $cpl->wife->mrn }} | Blood: {{ $cpl->wife->blood_group }} | Phone: {{ $cpl->wife->phone }}</span>
                                                </div>
                                                <div>
                                                    <strong>Male Partner (Husband):</strong> {{ $cpl->husband ? $cpl->husband->first_name . ' ' . $cpl->husband->last_name : 'Donor Sperm Protocol' }}<br>
                                                    <span class="mono text-muted">MRN: {{ $cpl->husband->mrn ?? 'N/A' }} | Blood: {{ $cpl->husband->blood_group ?? 'N/A' }} | Phone: {{ $cpl->husband->phone ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    <strong>Marriage Duration:</strong> {{ $cpl->marriage_years }} Years
                                                </div>
                                                <div>
                                                    <strong>Supervising Clinician:</strong> {{ $cpl->primary_doctor }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Statutory Clauses -->
                                        <div style="font-size: 11.5px; display: flex; flex-direction: column; gap: 10px;">
                                            <div style="border-left: 3px solid #000000; padding-left: 8px;">
                                                <strong>Clause 1: Informed Consent for IVF / ICSI & Ovum Pick-Up (OPU)</strong>
                                                <p style="margin-top: 3px; color: #222;">We hereby declare that we have been fully informed regarding the nature, clinical risks, medication side effects, conscious sedation risks, and realistic outcome probabilities of Controlled Ovarian Stimulation (COS), surgical transvaginal oocyte retrieval, and embryology procedures.</p>
                                            </div>
                                            <div style="border-left: 3px solid #000000; padding-left: 8px;">
                                                <strong>Clause 2: Cryopreservation & Liquid Nitrogen Bio-Storage Authorization</strong>
                                                <p style="margin-top: 3px; color: #222;">We explicitly consent to the vitrification and bio-repository storage of surplus viable blastocysts, oocytes, or semen in liquid nitrogen cryo-tanks (-196°C) as per the statutory guidelines of the ART Act.</p>
                                            </div>
                                            <div style="border-left: 3px solid #000000; padding-left: 8px;">
                                                <strong>Clause 3: Statutory Rights & Genetic Testing Direction</strong>
                                                <p style="margin-top: 3px; color: #222;">In accordance with national legal provisions, sex selection in any form is strictly prohibited. Genetic screening (PGT-A/M) shall be conducted solely for aneuploidy or severe monogenic disorder prevention.</p>
                                            </div>
                                        </div>

                                        <!-- Signatures Block -->
                                        <div style="margin-top: 1.5rem; border-top: 1px solid #000000; padding-top: 1rem; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; text-align: center; font-size: 11px;">
                                            <div style="border-top: 1px dashed #000000; padding-top: 6px; margin-top: 25px;">
                                                <strong>{{ $cpl->wife->first_name }} {{ $cpl->wife->last_name }}</strong><br>
                                                <span class="text-muted">Signature of Female Partner</span>
                                            </div>
                                            <div style="border-top: 1px dashed #000000; padding-top: 6px; margin-top: 25px;">
                                                <strong>{{ $cpl->husband ? $cpl->husband->first_name . ' ' . $cpl->husband->last_name : 'Donor Sperm Consented' }}</strong><br>
                                                <span class="text-muted">Signature of Male Partner</span>
                                            </div>
                                            <div style="border-top: 1px dashed #000000; padding-top: 6px; margin-top: 25px;">
                                                <strong>{{ $cpl->primary_doctor }}</strong><br>
                                                <span class="text-muted">Treating Reproductive Specialist</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
                                        <form action="{{ route('ivf.couples.update', $cpl->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="primary_doctor" value="{{ $cpl->primary_doctor }}">
                                            <input type="hidden" name="marriage_years" value="{{ $cpl->marriage_years }}">
                                            <input type="hidden" name="status" value="{{ $cpl->status }}">
                                            <input type="hidden" name="consent_status" value="{{ $cpl->consent_status === 'Verified' ? 'Pending' : 'Verified' }}">
                                            <button type="submit" class="btn btn-outline btn-sm">
                                                Mark Status as: {{ $cpl->consent_status === 'Verified' ? 'Pending' : 'Verified (Signed)' }}
                                            </button>
                                        </form>
                                        <div style="display: flex; gap: 8px;">
                                            <button type="button" class="btn btn-black btn-sm" onclick="window.print()">Print Legal Consent Form</button>
                                            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('consentModal_{{ $cpl->id }}')">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT COUPLE & WORKUP MODAL -->
                            <div class="modal-overlay" id="editCoupleModal_{{ $cpl->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Couple & Diagnostics: {{ $cpl->couple_code }}</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editCoupleModal_{{ $cpl->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.couples.update', $cpl->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div style="font-weight: 700; font-size: 12px; font-family: var(--font-mono); border-bottom: 1px solid var(--border-strong); padding-bottom: 4px;">
                                                1. COUPLE IDENTITY & MARITAL RECORD
                                            </div>
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Wife First Name</label>
                                                    <input type="text" name="wife_first_name" class="form-control" value="{{ $cpl->wife->first_name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Wife Last Name</label>
                                                    <input type="text" name="wife_last_name" class="form-control" value="{{ $cpl->wife->last_name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Wife Blood Group</label>
                                                    <input type="text" name="wife_blood_group" class="form-control" value="{{ $cpl->wife->blood_group }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Wife Mobile Number</label>
                                                    <input type="tel" name="wife_phone" class="form-control" value="{{ $cpl->wife->phone }}" placeholder="+91 98765 43210">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Marriage Duration (Years)</label>
                                                    <input type="number" name="marriage_years" class="form-control" value="{{ $cpl->marriage_years }}">
                                                </div>
                                                @if($cpl->husband)
                                                    <div class="form-group">
                                                        <label class="form-label">Husband First Name</label>
                                                        <input type="text" name="husband_first_name" class="form-control" value="{{ $cpl->husband->first_name }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Husband Last Name</label>
                                                        <input type="text" name="husband_last_name" class="form-control" value="{{ $cpl->husband->last_name }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Husband Mobile Number</label>
                                                        <input type="tel" name="husband_phone" class="form-control" value="{{ $cpl->husband->phone }}" placeholder="+91 98765 43210">
                                                    </div>
                                                @endif
                                                <div class="form-group">
                                                    <label class="form-label">Primary Physician</label>
                                                    <input type="text" name="primary_doctor" class="form-control" value="{{ $cpl->primary_doctor }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Legal Consent Status</label>
                                                    <select name="consent_status" class="form-control">
                                                        <option value="Verified" {{ $cpl->consent_status === 'Verified' ? 'selected' : '' }}>Verified (Signed)</option>
                                                        <option value="Pending" {{ $cpl->consent_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div style="font-weight: 700; font-size: 12px; font-family: var(--font-mono); border-bottom: 1px solid var(--border-strong); padding-bottom: 4px; margin-top: 1rem;">
                                                2. FEMALE OVARIAN RESERVE & DIAGNOSTICS
                                            </div>
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Serum AMH (ng/mL)</label>
                                                    <input type="number" step="0.01" name="female_amh" class="form-control" value="{{ $cpl->workup->female_amh ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Antral Follicle Count (AFC)</label>
                                                    <input type="number" name="female_afc" class="form-control" value="{{ $cpl->workup->female_afc ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Day 2/3 FSH (mIU/mL)</label>
                                                    <input type="number" step="0.01" name="female_fsh" class="form-control" value="{{ $cpl->workup->female_fsh ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Tubal Patency</label>
                                                    <input type="text" name="tubal_patency" class="form-control" value="{{ $cpl->workup->tubal_patency ?? 'Both Patent' }}">
                                                </div>
                                            </div>

                                            <div style="font-weight: 700; font-size: 12px; font-family: var(--font-mono); border-bottom: 1px solid var(--border-strong); padding-bottom: 4px; margin-top: 1rem;">
                                                3. MALE ANDROLOGY & SPERM PARAMETERS
                                            </div>
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Sperm Count (Million/mL)</label>
                                                    <input type="number" step="0.1" name="sperm_count_million" class="form-control" value="{{ $cpl->workup->sperm_count_million ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Progressive Motility (%)</label>
                                                    <input type="number" name="sperm_motility_percent" class="form-control" value="{{ $cpl->workup->sperm_motility_percent ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Kruger Strict Morphology (%)</label>
                                                    <input type="number" name="sperm_morphology_percent" class="form-control" value="{{ $cpl->workup->sperm_morphology_percent ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Sperm DFI (DNA Fragmentation %)</label>
                                                    <input type="number" name="sperm_dfi_percent" class="form-control" value="{{ $cpl->workup->sperm_dfi_percent ?? '' }}">
                                                </div>
                                                <div class="form-group" style="grid-column: 1 / -1;">
                                                    <label class="form-label">Clinical Diagnosis *</label>
                                                    <input type="text" name="clinical_diagnosis" class="form-control" value="{{ $cpl->workup->clinical_diagnosis ?? '' }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editCoupleModal_{{ $cpl->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Save Changes to MySQL</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- START CYCLE DIRECT MODAL FOR THIS COUPLE -->
                            <div class="modal-overlay" id="startCycleModal_{{ $cpl->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Start Treatment Cycle: {{ $cpl->couple_code }} ({{ $cpl->wife->first_name }} {{ $cpl->wife->last_name }})</div>
                                        <button type="button" class="modal-close" onclick="closeModal('startCycleModal_{{ $cpl->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.cycles.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="couple_id" value="{{ $cpl->id }}">
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group" style="grid-column: 1 / -1;">
                                                    <label class="form-label">Couple Record</label>
                                                    <input type="text" class="form-control" value="{{ $cpl->couple_code }} — {{ $cpl->wife->first_name }} {{ $cpl->wife->last_name }} & {{ $cpl->husband ? $cpl->husband->first_name . ' ' . $cpl->husband->last_name : 'No Partner' }} ({{ $cpl->wife->phone }})" readonly style="background: #f4f4f5;">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Protocol Name *</label>
                                                    <input type="text" name="protocol_name" class="form-control" list="protocolPresetsDirect_{{ $cpl->id }}" value="Flexible GnRH Antagonist Protocol" required>
                                                    <datalist id="protocolPresetsDirect_{{ $cpl->id }}">
                                                        <option value="Flexible GnRH Antagonist Protocol">
                                                        <option value="Standard Antagonist Protocol">
                                                        <option value="Long Agonist (Down-Regulation) Protocol">
                                                        <option value="Microdose Agonist Flare + Growth Hormone Priming">
                                                        <option value="Progestin-Primed Ovarian Stimulation (PPOS)">
                                                        <option value="Mild Stimulation / Natural Cycle IVF">
                                                        <option value="Frozen Embryo Transfer (FET)">
                                                    </datalist>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Cycle Type *</label>
                                                    <select name="cycle_type" class="form-control" required>
                                                        <option value="Self-ICSI">Self-ICSI</option>
                                                        <option value="Self-IVF">Self-IVF</option>
                                                        <option value="Donor Oocyte ICSI">Donor Oocyte ICSI</option>
                                                        <option value="Donor Sperm ICSI">Donor Sperm ICSI</option>
                                                        <option value="Frozen Embryo Transfer (FET)">Frozen Embryo Transfer (FET)</option>
                                                        <option value="PGT-A Screening Cycle">PGT-A Screening Cycle</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Cycle Start Date *</label>
                                                    <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Initial Status *</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="Stimulation">Stimulation (Folliculometry Active)</option>
                                                        <option value="OPU_Scheduled">OPU Scheduled</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" style="grid-column: 1 / -1;">
                                                    <label class="form-label">Clinical Protocol & Stimulation Notes</label>
                                                    <textarea name="cycle_notes" class="form-control" rows="2" placeholder="e.g. Dose rFSH 225 IU daily."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('startCycleModal_{{ $cpl->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">+ Initiate Treatment Cycle</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- NEW COUPLE & INFERTILITY WORKUP MODAL -->
        <div class="modal-overlay" id="newCoupleModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Register New Couple & Infertility Workup</div>
                    <button type="button" class="modal-close" onclick="closeModal('newCoupleModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.couples.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div style="font-weight: 700; font-size: 11.5px; font-family: var(--font-mono); border-bottom: 1px solid var(--border-strong); padding-bottom: 4px;">
                            1. FEMALE PARTNER (WIFE)
                        </div>
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="wife_first_name" class="form-control" placeholder="e.g. Priya" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="wife_last_name" class="form-control" placeholder="e.g. Sharma" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Blood Group</label>
                                <input type="text" name="wife_blood_group" class="form-control" placeholder="e.g. B Positive">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mobile Number (India) *</label>
                                <input type="tel" name="wife_phone" class="form-control" value="+91 " placeholder="+91 98765 43210" required>
                            </div>
                        </div>

                        <div style="font-weight: 700; font-size: 11.5px; font-family: var(--font-mono); border-bottom: 1px solid var(--border-strong); padding-bottom: 4px; margin-top: 1rem;">
                            2. MALE PARTNER (HUSBAND) / SPERM PROTOCOL
                        </div>
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" name="husband_first_name" class="form-control" placeholder="e.g. Rahul (Leave blank if donor)">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="husband_last_name" class="form-control" placeholder="e.g. Sharma">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Blood Group</label>
                                <input type="text" name="husband_blood_group" class="form-control" placeholder="e.g. O Positive">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mobile Number (India)</label>
                                <input type="tel" name="husband_phone" class="form-control" value="+91 " placeholder="+91 98765 43210">
                            </div>
                        </div>

                        <div style="font-weight: 700; font-size: 11.5px; font-family: var(--font-mono); border-bottom: 1px solid var(--border-strong); padding-bottom: 4px; margin-top: 1rem;">
                            3. MARITAL & LEGAL DETAILS
                        </div>
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group">
                                <label class="form-label">Marriage Duration (Years) *</label>
                                <input type="number" name="marriage_years" class="form-control" value="3" min="0" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Attending Physician *</label>
                                <input type="text" name="primary_doctor" class="form-control" value="Dr. Elena Vance, MD" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Legal Consent Status *</label>
                                <select name="consent_status" class="form-control" required>
                                    <option value="Verified">Verified (Signed)</option>
                                    <option value="Pending" selected>Pending Review</option>
                                </select>
                            </div>
                        </div>

                        <div style="font-weight: 700; font-size: 11.5px; font-family: var(--font-mono); border-bottom: 1px solid var(--border-strong); padding-bottom: 4px; margin-top: 1rem;">
                            4. INITIAL DIAGNOSTIC WORKUP
                        </div>
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group">
                                <label class="form-label">Serum AMH (ng/mL)</label>
                                <input type="number" step="0.01" name="female_amh" class="form-control" placeholder="e.g. 2.45">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Antral Follicles (AFC)</label>
                                <input type="number" name="female_afc" class="form-control" placeholder="e.g. 14">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Day 2/3 FSH (mIU/mL)</label>
                                <input type="number" step="0.01" name="female_fsh" class="form-control" placeholder="e.g. 6.5">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tubal Patency</label>
                                <input type="text" name="tubal_patency" class="form-control" value="Both Patent">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sperm Count (Million/mL)</label>
                                <input type="number" step="0.1" name="sperm_count_million" class="form-control" placeholder="e.g. 24.0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Progressive Motility (%)</label>
                                <input type="number" name="sperm_motility_percent" class="form-control" placeholder="e.g. 42">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kruger Morphology (%)</label>
                                <input type="number" name="sperm_morphology_percent" class="form-control" placeholder="e.g. 4">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sperm DFI (%)</label>
                                <input type="number" name="sperm_dfi_percent" class="form-control" placeholder="e.g. 15">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Clinical Diagnosis *</label>
                                <input type="text" name="clinical_diagnosis" class="form-control" placeholder="e.g. Primary Infertility with Mild Male Factor" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newCoupleModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">+ Register Couple & Infertility Workup</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 4: STIMULATION & FOLLICULAR SCANS -->
    <!-- ===================================================================== -->
    @if($activeTab === 'cycles')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Controlled Ovarian Stimulation & Folliculometry</div>
                    <div class="panel-desc">Modify treatment protocols, log daily ultrasound scans, and update trigger timestamps</div>
                </div>
                <div class="panel-actions" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <input type="text" id="cycleSearchInput" class="form-control" style="width: 220px; font-size: 12px; padding: 5px 10px;" placeholder="🔍 Filter cycles table..." onkeyup="filterTable('cyclesTableBody', this.value)" autocomplete="off">
                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('newCycleModal')">+ Start Treatment Cycle</button>
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newScanModal')">+ Record Ultrasound Scan</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>Cycle ID</th>
                            <th>Couple</th>
                            <th>Protocol</th>
                            <th>Attempt</th>
                            <th>Start Date</th>
                            <th>Trigger Timestamp</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cyclesTableBody">
                        @foreach($cycles as $cyc)
                            <tr>
                                <td class="mono font-bold">CYC-{{ $cyc->id }}</td>
                                <td>
                                    <strong>{{ $cyc->couple->wife->first_name ?? 'Patient' }} {{ $cyc->couple->wife->last_name ?? '' }}</strong>
                                    <div class="mono text-muted" style="font-size: 10.5px;">{{ $cyc->couple->couple_code }}</div>
                                </td>
                                <td>{{ $cyc->protocol_name }}</td>
                                <td>
                                    @if($cyc->is_reattempt)
                                        <span class="tag tag-black">RE-ATTEMPT #{{ $cyc->cycle_number }} (PREV: CYC-{{ $cyc->previous_cycle_id }})</span>
                                    @else
                                        <span class="tag tag-outline">CYCLE #{{ $cyc->cycle_number }}</span>
                                    @endif
                                </td>
                                <td class="mono">{{ $cyc->start_date }}</td>
                                <td class="mono">
                                    {{ $cyc->trigger_datetime ?? 'Trigger Pending' }}
                                    @if($cyc->trigger_drug)
                                        <div class="text-muted" style="font-size: 10.5px;">{{ $cyc->trigger_drug }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="tag {{ $cyc->status === 'Stimulation' ? 'tag-black' : 'tag-outline' }}">
                                        {{ str_replace('_', ' ', $cyc->status) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editCycleModal_{{ $cyc->id }}')">Edit Cycle</button>
                                    <form action="{{ route('ivf.cycles.delete', $cyc->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove cycle CYC-{{ $cyc->id }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Del</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- EDIT CYCLE MODAL -->
                            <div class="modal-overlay" id="editCycleModal_{{ $cyc->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Treatment Cycle: CYC-{{ $cyc->id }}</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editCycleModal_{{ $cyc->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.cycles.update', $cyc->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Protocol Name *</label>
                                                    <input type="text" name="protocol_name" class="form-control" value="{{ $cyc->protocol_name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Cycle Type</label>
                                                    <input type="text" name="cycle_type" class="form-control" value="{{ $cyc->cycle_type }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Cycle Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="Stimulation" {{ $cyc->status === 'Stimulation' ? 'selected' : '' }}>Stimulation</option>
                                                        <option value="OPU_Scheduled" {{ $cyc->status === 'OPU_Scheduled' ? 'selected' : '' }}>OPU Scheduled</option>
                                                        <option value="OPU_Completed" {{ $cyc->status === 'OPU_Completed' ? 'selected' : '' }}>OPU Completed</option>
                                                        <option value="Embryo_Culture" {{ $cyc->status === 'Embryo_Culture' ? 'selected' : '' }}>Embryo Culture</option>
                                                        <option value="Transfer_Completed" {{ $cyc->status === 'Transfer_Completed' ? 'selected' : '' }}>Transfer Completed</option>
                                                        <option value="Outcome_Positive" {{ $cyc->status === 'Outcome_Positive' ? 'selected' : '' }}>Outcome Positive</option>
                                                        <option value="Outcome_Failed" {{ $cyc->status === 'Outcome_Failed' ? 'selected' : '' }}>Outcome Failed</option>
                                                        <option value="Cancelled" {{ $cyc->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Stimulation Start Date</label>
                                                    <input type="date" name="start_date" class="form-control" value="{{ $cyc->start_date }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Trigger Date & Time</label>
                                                    <input type="text" name="trigger_datetime" class="form-control" value="{{ $cyc->trigger_datetime }}" placeholder="YYYY-MM-DD HH:MM:SS">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Trigger Drug & Dose</label>
                                                    <input type="text" name="trigger_drug" class="form-control" value="{{ $cyc->trigger_drug }}" placeholder="e.g. Ovitrelle 250 mcg">
                                                </div>
                                                <div class="form-group" style="grid-column: 1 / -1;">
                                                    <label class="form-label">Cycle Clinical Notes</label>
                                                    <textarea name="cycle_notes" class="form-control">{{ $cyc->cycle_notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editCycleModal_{{ $cyc->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Save Cycle</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RECENT FOLLICULAR ULTRASOUND SCANS TABLE -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Follicular Scans Log (Folliculometry)</div>
            </div>
            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Cycle</th>
                            <th>Patient</th>
                            <th>Day</th>
                            <th>Right Ovary (Follicles mm)</th>
                            <th>Left Ovary (Follicles mm)</th>
                            <th>Endometrium</th>
                            <th>E2 Level</th>
                            <th>Medication Dosing</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scans as $scan)
                            <tr>
                                <td class="mono">{{ $scan->scan_date }}</td>
                                <td class="mono font-bold">CYC-{{ $scan->cycle_id }}</td>
                                <td>{{ $scan->cycle->couple->wife->first_name }} {{ $scan->cycle->couple->wife->last_name }}</td>
                                <td class="mono">Day {{ $scan->cycle_day }}</td>
                                <td class="mono font-bold">{{ $scan->right_ovary_follicles }}</td>
                                <td class="mono font-bold">{{ $scan->left_ovary_follicles }}</td>
                                <td>
                                    <span class="mono font-bold">{{ $scan->endometrium_thickness_mm }} mm</span>
                                    <span class="tag tag-outline" style="font-size: 9.5px; margin-left: 2px;">{{ $scan->endometrium_pattern }}</span>
                                </td>
                                <td class="mono">{{ $scan->estradiol_e2_pg_ml ? $scan->estradiol_e2_pg_ml . ' pg/mL' : '—' }}</td>
                                <td>{{ $scan->gonadotropin_dose_given ?? '—' }}</td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editScanModal_{{ $scan->id }}')">Edit</button>
                                    <form action="{{ route('ivf.scans.delete', $scan->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete scan record for Day {{ $scan->cycle_day }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Del</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- EDIT SCAN MODAL -->
                            <div class="modal-overlay" id="editScanModal_{{ $scan->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Scan Record: CYC-{{ $scan->cycle_id }} (Day {{ $scan->cycle_day }})</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editScanModal_{{ $scan->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.scans.update', $scan->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Scan Date *</label>
                                                    <input type="date" name="scan_date" class="form-control" value="{{ $scan->scan_date }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Day of Stimulation *</label>
                                                    <input type="number" name="cycle_day" class="form-control" value="{{ $scan->cycle_day }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Right Ovary Follicles *</label>
                                                    <input type="text" name="right_ovary_follicles" class="form-control" value="{{ $scan->right_ovary_follicles }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Left Ovary Follicles *</label>
                                                    <input type="text" name="left_ovary_follicles" class="form-control" value="{{ $scan->left_ovary_follicles }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Endometrium (mm) *</label>
                                                    <input type="number" step="0.1" name="endometrium_thickness_mm" class="form-control" value="{{ $scan->endometrium_thickness_mm }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Endometrial Pattern</label>
                                                    <select name="endometrium_pattern" class="form-control">
                                                        <option value="Trilaminar" {{ $scan->endometrium_pattern === 'Trilaminar' ? 'selected' : '' }}>Trilaminar</option>
                                                        <option value="Homogenous" {{ $scan->endometrium_pattern === 'Homogenous' ? 'selected' : '' }}>Homogenous</option>
                                                        <option value="Hyperechoic" {{ $scan->endometrium_pattern === 'Hyperechoic' ? 'selected' : '' }}>Hyperechoic</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Estradiol E2 (pg/mL)</label>
                                                    <input type="number" step="0.1" name="estradiol_e2_pg_ml" class="form-control" value="{{ $scan->estradiol_e2_pg_ml }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Medication Dose</label>
                                                    <input type="text" name="gonadotropin_dose_given" class="form-control" value="{{ $scan->gonadotropin_dose_given }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editScanModal_{{ $scan->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Update Scan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- START NEW TREATMENT CYCLE MODAL -->
        <div class="modal-overlay" id="newCycleModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Start New Treatment Cycle</div>
                    <button type="button" class="modal-close" onclick="closeModal('newCycleModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.cycles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Select Couple *</label>
                                <input type="text" id="new_cycle_couple_search" class="form-control mb-1" placeholder="🔍 Type to search couple by wife/husband name, phone, code..." oninput="filterSelectOptions('new_cycle_couple_select', this.value)" autocomplete="off">
                                <select name="couple_id" id="new_cycle_couple_select" class="form-control" required size="5" style="height: auto; max-height: 150px; padding: 4px;">
                                    @foreach($couples as $cpl)
                                        <option value="{{ $cpl->id }}" data-search="{{ strtolower(($cpl->wife->first_name ?? '') . ' ' . ($cpl->wife->last_name ?? '') . ' ' . ($cpl->husband->first_name ?? '') . ' ' . ($cpl->husband->last_name ?? '') . ' ' . $cpl->couple_code . ' ' . ($cpl->wife->phone ?? '')) }}" {{ $loop->first ? 'selected' : '' }}>
                                            {{ $cpl->couple_code }} — {{ $cpl->wife->first_name ?? 'Wife' }} {{ $cpl->wife->last_name ?? '' }} &amp; {{ $cpl->husband ? $cpl->husband->first_name . ' ' . $cpl->husband->last_name : 'No Partner' }} ({{ $cpl->wife->phone ?? 'No Phone' }}) [Cycles: {{ $cpl->cycles->count() }}]
                                        </option>
                                    @endforeach
                                </select>
                                <div style="font-size: 11px; margin-top: 4px; color: #666; display: flex; justify-content: space-between;">
                                    <span><span id="new_cycle_couple_select_count">{{ count($couples) }}</span> couples registered</span>
                                    <a href="javascript:void(0)" onclick="closeModal('newCycleModal'); openModal('newCoupleModal');" style="color: #000; text-decoration: underline; font-weight: bold;">+ Register New Couple &rarr;</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Protocol Name *</label>
                                <input type="text" name="protocol_name" class="form-control" list="protocolPresets" value="Flexible GnRH Antagonist Protocol" required>
                                <datalist id="protocolPresets">
                                    <option value="Flexible GnRH Antagonist Protocol">
                                    <option value="Standard Antagonist Protocol">
                                    <option value="Long Agonist (Down-Regulation) Protocol">
                                    <option value="Microdose Agonist Flare + Growth Hormone Priming">
                                    <option value="Progestin-Primed Ovarian Stimulation (PPOS)">
                                    <option value="Mild Stimulation / Natural Cycle IVF">
                                    <option value="Frozen Embryo Transfer (HRT Protocol)">
                                    <option value="Modified Natural FET Protocol">
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cycle Type *</label>
                                <select name="cycle_type" class="form-control" required>
                                    <option value="Self-ICSI">Self-ICSI (Intracytoplasmic Sperm Injection)</option>
                                    <option value="Self-IVF">Self-IVF (Conventional Insemination)</option>
                                    <option value="Donor Oocyte ICSI">Donor Oocyte ICSI</option>
                                    <option value="Donor Sperm ICSI">Donor Sperm ICSI</option>
                                    <option value="Frozen Embryo Transfer (FET)">Frozen Embryo Transfer (FET)</option>
                                    <option value="PGT-A Screening Cycle">PGT-A Screening Cycle</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cycle Start Date *</label>
                                <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Initial Status *</label>
                                <select name="status" class="form-control" required>
                                    <option value="Stimulation">Stimulation (Folliculometry Active)</option>
                                    <option value="OPU_Scheduled">OPU Scheduled</option>
                                    <option value="OPU_Completed">OPU Completed</option>
                                    <option value="Embryo_Culture">Embryo Culture</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Trigger Target / Plan</label>
                                <input type="text" name="trigger_drug" class="form-control" placeholder="e.g. Ovitrelle 250mcg / Decapeptyl 0.2mg">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Trigger DateTime (if scheduled)</label>
                                <input type="text" name="trigger_datetime" class="form-control" placeholder="YYYY-MM-DD HH:MM:SS">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Clinical Protocol &amp; Stimulation Notes</label>
                                <textarea name="cycle_notes" class="form-control" rows="2" placeholder="e.g. Starting dose rFSH 225 IU daily + HMG 75 IU. Dual trigger planned due to POR."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newCycleModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">+ Initiate Treatment Cycle</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- NEW SCAN MODAL -->
        <div class="modal-overlay" id="newScanModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Record Follicular Ultrasound Scan</div>
                    <button type="button" class="modal-close" onclick="closeModal('newScanModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.scans.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Select Treatment Cycle / Couple *</label>
                                <input type="text" id="scan_cycle_search" class="form-control mb-1" placeholder="🔍 Type patient / wife name, cycle ID or code to filter..." oninput="filterSelectOptions('scan_cycle_select', this.value)" autocomplete="off">
                                <select name="cycle_id" id="scan_cycle_select" class="form-control" required size="5" style="height: auto; max-height: 160px; padding: 4px;">
                                    <optgroup label="── Active Treatment Cycles ──">
                                        @foreach($cycles as $c)
                                            <option value="{{ $c->id }}" data-search="{{ strtolower(($c->couple->wife->first_name ?? '') . ' ' . ($c->couple->wife->last_name ?? '') . ' ' . ($c->couple->husband->first_name ?? '') . ' ' . ($c->couple->husband->last_name ?? '') . ' ' . $c->couple->couple_code . ' cyc-' . $c->id . ' ' . $c->protocol_name) }}" {{ $loop->first ? 'selected' : '' }}>
                                                CYC-{{ $c->id }} — {{ $c->couple->wife->first_name ?? 'Wife' }} {{ $c->couple->wife->last_name ?? '' }} ({{ $c->couple->couple_code }}) — {{ $c->protocol_name }} [{{ str_replace('_', ' ', $c->status) }}]
                                            </option>
                                        @endforeach
                                    </optgroup>
                                    @php
                                        $couplesWithoutCycle = $couples->filter(function($cpl) { return $cpl->cycles->isEmpty(); });
                                    @endphp
                                    @if($couplesWithoutCycle->isNotEmpty())
                                        <optgroup label="── Registered Couples (Auto-starts Cycle #1) ──">
                                            @foreach($couplesWithoutCycle as $cpl)
                                                <option value="new_cpl_{{ $cpl->id }}" data-search="{{ strtolower(($cpl->wife->first_name ?? '') . ' ' . ($cpl->wife->last_name ?? '') . ' ' . ($cpl->husband->first_name ?? '') . ' ' . ($cpl->husband->last_name ?? '') . ' ' . $cpl->couple_code . ' ' . ($cpl->wife->phone ?? '')) }}">
                                                    ➕ {{ $cpl->couple_code }} — {{ $cpl->wife->first_name ?? 'Wife' }} {{ $cpl->wife->last_name ?? '' }} &amp; {{ $cpl->husband ? $cpl->husband->first_name : 'No Partner' }} ({{ $cpl->wife->phone ?? '' }}) [Auto-start Cycle #1]
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                                <div style="font-size: 11px; margin-top: 4px; color: #666; display: flex; justify-content: space-between;">
                                    <span><span id="scan_cycle_select_count">{{ count($cycles) + count($couplesWithoutCycle) }}</span> treatment choices available</span>
                                    <a href="javascript:void(0)" onclick="closeModal('newScanModal'); openModal('newCycleModal');" style="color: #000; text-decoration: underline; font-weight: bold;">+ Or Start a Full Treatment Cycle &rarr;</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Scan Date *</label>
                                <input type="date" name="scan_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Day of Stimulation *</label>
                                <input type="number" name="cycle_day" class="form-control" value="8" min="1" max="25" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Right Ovary Follicles (mm) *</label>
                                <input type="text" name="right_ovary_follicles" class="form-control" placeholder="e.g. 18mm, 16mm, 14mm" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Left Ovary Follicles (mm) *</label>
                                <input type="text" name="left_ovary_follicles" class="form-control" placeholder="e.g. 19mm, 17mm, 15mm" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Endometrial Thickness (mm) *</label>
                                <input type="number" step="0.1" name="endometrium_thickness_mm" class="form-control" value="8.5" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Endometrial Pattern</label>
                                <select name="endometrium_pattern" class="form-control">
                                    <option value="Trilaminar">Trilaminar (Grade A)</option>
                                    <option value="Homogenous">Homogenous (Grade B)</option>
                                    <option value="Hyperechoic">Hyperechoic (Grade C)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Serum Estradiol (E2 pg/mL)</label>
                                <input type="number" step="0.1" name="estradiol_e2_pg_ml" class="form-control" placeholder="e.g. 1450.0">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Current Gonadotropin Dose Given</label>
                                <input type="text" name="gonadotropin_dose_given" class="form-control" placeholder="e.g. rFSH 225 IU + Cetrotide 0.25mg">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newScanModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">Save Scan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 5: OPU & EMBRYOLOGY LAB -->
    <!-- ===================================================================== -->
    @if($activeTab === 'laboratory')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">OPU Surgical Retrieval & Embryology Lab (ELIS)</div>
                    <div class="panel-desc">Record oocyte yields, maturity (MII), 2PN fertilization counts, and Gardner blastocyst grades</div>
                </div>
                <div class="panel-actions" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <input type="text" id="labSearchInput" class="form-control" style="width: 220px; font-size: 12px; padding: 5px 10px;" placeholder="🔍 Filter lab records..." onkeyup="filterTable('labTableBody', this.value)" autocomplete="off">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newLabModal')">+ Record OPU &amp; Lab Data</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>Cycle</th>
                            <th>Patient</th>
                            <th>OPU Date</th>
                            <th>Oocytes Retrieved</th>
                            <th>Maturity (MII / MI / GV)</th>
                            <th>Insemination Method</th>
                            <th>Fertilization (2PN)</th>
                            <th>D3 Cleaved</th>
                            <th>D5 Blasts</th>
                            <th style="min-width: 260px; max-width: 340px;">Blastocyst Gardner Grades</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="labTableBody">
                        @foreach($labRecords as $lab)
                            <tr>
                                <td class="mono font-bold">CYC-{{ $lab->cycle_id }}</td>
                                <td>
                                    <strong>{{ $lab->cycle->couple->wife->first_name ?? 'Patient' }} {{ $lab->cycle->couple->wife->last_name ?? '' }}</strong>
                                    <div class="mono text-muted" style="font-size: 10.5px;">{{ $lab->cycle->couple->couple_code ?? '' }}</div>
                                </td>
                                <td class="mono">{{ $lab->opu_date }}</td>
                                <td class="mono font-bold">{{ $lab->oocytes_retrieved }} Eggs</td>
                                <td class="mono">
                                    <strong>{{ $lab->mii_mature_count }} MII</strong> | {{ $lab->mi_count }} MI | {{ $lab->gv_count }} GV
                                </td>
                                <td>{{ $lab->insemination_method }}</td>
                                <td class="mono font-bold">{{ $lab->fertilized_2pn_count }} (2PN)</td>
                                <td class="mono">{{ $lab->day3_cleaved_count }} Cleaved</td>
                                <td class="mono font-bold">{{ $lab->day5_blast_count }} Blasts</td>
                                <td style="max-width: 340px; min-width: 240px; white-space: normal;">
                                    <span class="tag tag-black mono" style="white-space: normal; display: inline-block; word-break: break-word; line-height: 1.45; padding: 5px 8px; border-radius: 0;">{{ $lab->blastocyst_grades ?? 'Arrested' }}</span>
                                </td>
                                <td class="text-right" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editLabModal_{{ $lab->id }}')">Edit Lab Data</button>
                                    <form action="{{ route('ivf.lab.delete', $lab->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove lab record for CYC-{{ $lab->cycle_id }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Del</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- EDIT LAB MODAL -->
                            <div class="modal-overlay" id="editLabModal_{{ $lab->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Embryology Lab Record: CYC-{{ $lab->cycle_id }}</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editLabModal_{{ $lab->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.lab.update', $lab->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">OPU Retrieval Date *</label>
                                                    <input type="date" name="opu_date" class="form-control" value="{{ $lab->opu_date }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Total Oocytes Retrieved *</label>
                                                    <input type="number" name="oocytes_retrieved" class="form-control" value="{{ $lab->oocytes_retrieved }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">MII Mature Oocytes *</label>
                                                    <input type="number" name="mii_mature_count" class="form-control" value="{{ $lab->mii_mature_count }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">MI Intermediate Count</label>
                                                    <input type="number" name="mi_count" class="form-control" value="{{ $lab->mi_count }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Germinal Vesicle (GV) Count</label>
                                                    <input type="number" name="gv_count" class="form-control" value="{{ $lab->gv_count }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Fertilized Oocytes (2PN) *</label>
                                                    <input type="number" name="fertilized_2pn_count" class="form-control" value="{{ $lab->fertilized_2pn_count }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Day 3 Cleaved Count</label>
                                                    <input type="number" name="day3_cleaved_count" class="form-control" value="{{ $lab->day3_cleaved_count }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Day 5/6 Usable Blastocysts</label>
                                                    <input type="number" name="day5_blast_count" class="form-control" value="{{ $lab->day5_blast_count }}">
                                                </div>
                                                <div class="form-group" style="grid-column: 1 / -1;">
                                                    <label class="form-label">Blastocyst Gardner Grades (e.g. 4AA, 4AB)</label>
                                                    <input type="text" name="blastocyst_grades" class="form-control" value="{{ $lab->blastocyst_grades }}">
                                                </div>
                                                <div class="form-group" style="grid-column: 1 / -1;">
                                                    <label class="form-label">Insemination Technique</label>
                                                    <input type="text" name="insemination_method" class="form-control" value="{{ $lab->insemination_method }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editLabModal_{{ $lab->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Save Lab Record</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    

        <!-- NEW LAB RECORD MODAL -->
        <div class="modal-overlay" id="newLabModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Record OPU Retrieval &amp; Embryology Lab Data</div>
                    <button type="button" class="modal-close" onclick="closeModal('newLabModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.lab.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Select Treatment Cycle *</label>
                                <input type="text" id="new_lab_cycle_search" class="form-control mb-1" placeholder="🔍 Type patient name, couple code, or cycle ID to filter..." oninput="filterSelectOptions('new_lab_cycle_select', this.value)" autocomplete="off">
                                <select name="cycle_id" id="new_lab_cycle_select" class="form-control" required size="4" style="height: auto; max-height: 140px; padding: 4px;">
                                    @foreach($cycles as $c)
                                        <option value="{{ $c->id }}" data-search="{{ strtolower(($c->couple->wife->first_name ?? '') . ' ' . ($c->couple->wife->last_name ?? '') . ' ' . ($c->couple->husband->first_name ?? '') . ' ' . ($c->couple->husband->last_name ?? '') . ' ' . $c->couple->couple_code . ' cyc-' . $c->id . ' ' . $c->protocol_name) }}" {{ $loop->first ? 'selected' : '' }}>
                                            CYC-{{ $c->id }} — {{ $c->couple->wife->first_name ?? 'Wife' }} {{ $c->couple->wife->last_name ?? '' }} ({{ $c->couple->couple_code }}) [{{ str_replace('_', ' ', $c->status) }}]
                                        </option>
                                    @endforeach
                                </select>
                                <div style="font-size: 11px; margin-top: 4px; color: #666;">
                                    <span id="new_lab_cycle_select_count">{{ count($cycles) }}</span> cycles available
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">OPU Retrieval Date *</label>
                                <input type="date" name="opu_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Total Oocytes Retrieved (Eggs) *</label>
                                <input type="number" name="oocytes_retrieved" class="form-control" value="12" min="0" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">MII Mature Oocytes *</label>
                                <input type="number" name="mii_mature_count" class="form-control" value="10" min="0" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">MI Intermediate Count</label>
                                <input type="number" name="mi_count" class="form-control" value="1" min="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Germinal Vesicle (GV) Count</label>
                                <input type="number" name="gv_count" class="form-control" value="1" min="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fertilized Oocytes (2PN) *</label>
                                <input type="number" name="fertilized_2pn_count" class="form-control" value="8" min="0" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Day 3 Cleaved Embryos</label>
                                <input type="number" name="day3_cleaved_count" class="form-control" value="7" min="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Day 5/6 Usable Blastocysts</label>
                                <input type="number" name="day5_blast_count" class="form-control" value="4" min="0">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Blastocyst Gardner Grades (e.g. Day 5: 4AA, 4AB, 3BB)</label>
                                <textarea name="blastocyst_grades" class="form-control" rows="2" placeholder="e.g. Day 5: 4AA (selected for Fresh ET), 4AB (vitrified), 4BB (vitrified)."></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Insemination Technique</label>
                                <input type="text" name="insemination_method" class="form-control" value="ICSI" list="insemMethods">
                                <datalist id="insemMethods">
                                    <option value="ICSI">
                                    <option value="Conventional IVF">
                                    <option value="ICSI with Zymot Microfluidic Sperm Sorting">
                                    <option value="ICSI + Calcium Ionophore Oocyte Activation">
                                    <option value="IMSI (High Magnification)">
                                    <option value="PICSI (Physiological ICSI)">
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Attending Embryologist</label>
                                <input type="text" name="embryologist_name" class="form-control" value="Dr. Marcus Sterling, Lead Embryologist">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newLabModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">+ Save Lab Record</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 6: EMBRYO TRANSFERS & BETA-hCG -->
    <!-- ===================================================================== -->
    @if($activeTab === 'transfers')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Embryo Transfer &amp; Early Pregnancy Outcome Tracking</div>
                    <div class="panel-desc">Record transfer parameters, catheter selection, Beta-hCG blood test titers, and viability scans</div>
                </div>
                <div class="panel-actions" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <input type="text" id="transferSearchInput" class="form-control" style="width: 220px; font-size: 12px; padding: 5px 10px;" placeholder="🔍 Filter transfers..." onkeyup="filterTable('transferTableBody', this.value)" autocomplete="off">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newTransferModal')">+ Record Embryo Transfer</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>Cycle</th>
                            <th>Patient / Couple</th>
                            <th>Transfer Type</th>
                            <th>Date</th>
                            <th>Embryos Selected</th>
                            <th>Catheter & Ease</th>
                            <th>Day 14 Beta-hCG</th>
                            <th>Clinical Pregnancy</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="transferTableBody">
                        @foreach($transfers as $et)
                            @php
                                $outcome = $et->cycle->outcome;
                            @endphp
                            <tr>
                                <td class="mono font-bold">CYC-{{ $et->cycle_id }}</td>
                                <td>
                                    <strong>{{ $et->cycle->couple->wife->first_name ?? 'Patient' }} {{ $et->cycle->couple->wife->last_name ?? '' }}</strong>
                                    <div class="mono text-muted" style="font-size: 10.5px;">{{ $et->cycle->couple->couple_code ?? '' }}</div>
                                </td>
                                <td><span class="tag tag-black">{{ $et->transfer_type }}</span></td>
                                <td class="mono">{{ $et->transfer_date }}</td>
                                <td><strong>{{ $et->embryos_selected }}</strong></td>
                                <td>
                                    {{ $et->catheter_type }}
                                    <div class="text-muted" style="font-size: 10.5px;">Ease: {{ $et->ease_of_transfer }}</div>
                                </td>
                                <td class="mono font-bold">
                                    {{ $outcome && $outcome->betahcg_day14 ? $outcome->betahcg_day14 . ' mIU/mL' : 'Pending' }}
                                </td>
                                <td>
                                    @if($outcome && $outcome->result_status === 'Positive')
                                        <span class="tag tag-black">POSITIVE ({{ $outcome->gestational_sacs }} SAC, HEARTBEAT +)</span>
                                    @else
                                        <span class="tag tag-outline">{{ $outcome->result_status ?? 'Pending' }}</span>
                                    @endif
                                </td>
                                <td class="text-right" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editTransferModal_{{ $et->id }}')">Edit ET</button>
                                    @if($outcome)
                                        <button type="button" class="btn btn-black btn-sm" onclick="openModal('editOutcomeModal_{{ $outcome->id }}')">Edit hCG</button>
                                    @endif
                                    <form action="{{ route('ivf.transfers.delete', $et->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove transfer record for CYC-{{ $et->cycle_id }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Del</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- EDIT TRANSFER MODAL -->
                            <div class="modal-overlay" id="editTransferModal_{{ $et->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Transfer Record: CYC-{{ $et->cycle_id }}</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editTransferModal_{{ $et->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.transfers.update', $et->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Transfer Type</label>
                                                    <select name="transfer_type" class="form-control">
                                                        <option value="Fresh ET" {{ $et->transfer_type === 'Fresh ET' ? 'selected' : '' }}>Fresh ET</option>
                                                        <option value="Frozen Embryo Transfer (FET)" {{ $et->transfer_type === 'Frozen Embryo Transfer (FET)' ? 'selected' : '' }}>Frozen Embryo Transfer (FET)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Transfer Date *</label>
                                                    <input type="date" name="transfer_date" class="form-control" value="{{ $et->transfer_date }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Embryos Selected *</label>
                                                    <input type="text" name="embryos_selected" class="form-control" value="{{ $et->embryos_selected }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Catheter Type</label>
                                                    <input type="text" name="catheter_type" class="form-control" value="{{ $et->catheter_type }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editTransferModal_{{ $et->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Save Transfer Record</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- EDIT OUTCOME MODAL -->
                            @if($outcome)
                                <div class="modal-overlay" id="editOutcomeModal_{{ $outcome->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-header">
                                            <div class="modal-title">Edit Pregnancy Outcome & Beta-hCG: CYC-{{ $et->cycle_id }}</div>
                                            <button type="button" class="modal-close" onclick="closeModal('editOutcomeModal_{{ $outcome->id }}')">&times;</button>
                                        </div>
                                        <form action="{{ route('ivf.outcomes.update', $outcome->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-grid" style="padding: 0;">
                                                    <div class="form-group">
                                                        <label class="form-label">Day 14 Serum Beta-hCG (mIU/mL)</label>
                                                        <input type="number" step="0.01" name="betahcg_day14" class="form-control" value="{{ $outcome->betahcg_day14 }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Day 16 Serum Beta-hCG (mIU/mL)</label>
                                                        <input type="number" step="0.01" name="betahcg_day16" class="form-control" value="{{ $outcome->betahcg_day16 }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Clinical Pregnancy Status *</label>
                                                        <select name="result_status" class="form-control">
                                                            <option value="Positive" {{ $outcome->result_status === 'Positive' ? 'selected' : '' }}>Positive (Intrauterine Sac)</option>
                                                            <option value="Negative" {{ $outcome->result_status === 'Negative' ? 'selected' : '' }}>Negative (< 5 mIU/mL)</option>
                                                            <option value="Biochemical Pregnancy" {{ $outcome->result_status === 'Biochemical Pregnancy' ? 'selected' : '' }}>Biochemical Pregnancy</option>
                                                            <option value="Clinical Miscarriage" {{ $outcome->result_status === 'Clinical Miscarriage' ? 'selected' : '' }}>Clinical Miscarriage</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Gestational Sacs Count</label>
                                                        <input type="number" name="gestational_sacs" class="form-control" value="{{ $outcome->gestational_sacs }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline" onclick="closeModal('editOutcomeModal_{{ $outcome->id }}')">Cancel</button>
                                                <button type="submit" class="btn btn-black">Save Outcome</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    

        <!-- NEW EMBRYO TRANSFER MODAL -->
        <div class="modal-overlay" id="newTransferModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Record Embryo Transfer &amp; Beta-hCG Plan</div>
                    <button type="button" class="modal-close" onclick="closeModal('newTransferModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.transfers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Select Treatment Cycle *</label>
                                <input type="text" id="new_transfer_cycle_search" class="form-control mb-1" placeholder="🔍 Type patient name, couple code, or cycle ID to filter..." oninput="filterSelectOptions('new_transfer_cycle_select', this.value)" autocomplete="off">
                                <select name="cycle_id" id="new_transfer_cycle_select" class="form-control" required size="4" style="height: auto; max-height: 140px; padding: 4px;">
                                    @foreach($cycles as $c)
                                        <option value="{{ $c->id }}" data-search="{{ strtolower(($c->couple->wife->first_name ?? '') . ' ' . ($c->couple->wife->last_name ?? '') . ' ' . ($c->couple->husband->first_name ?? '') . ' ' . ($c->couple->husband->last_name ?? '') . ' ' . $c->couple->couple_code . ' cyc-' . $c->id . ' ' . $c->protocol_name) }}" {{ $loop->first ? 'selected' : '' }}>
                                            CYC-{{ $c->id }} — {{ $c->couple->wife->first_name ?? 'Wife' }} {{ $c->couple->wife->last_name ?? '' }} ({{ $c->couple->couple_code }}) [{{ str_replace('_', ' ', $c->status) }}]
                                        </option>
                                    @endforeach
                                </select>
                                <div style="font-size: 11px; margin-top: 4px; color: #666;">
                                    <span id="new_transfer_cycle_select_count">{{ count($cycles) }}</span> cycles available
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Transfer Type *</label>
                                <select name="transfer_type" class="form-control" required>
                                    <option value="Fresh ET">Fresh ET (Day 5 Blastocyst)</option>
                                    <option value="Frozen Embryo Transfer (FET)">Frozen Embryo Transfer (FET)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Transfer Date *</label>
                                <input type="date" name="transfer_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Embryos Transferred Count *</label>
                                <input type="number" name="embryos_transferred_count" class="form-control" value="1" min="1" max="4" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Embryos Selected &amp; Grade *</label>
                                <input type="text" name="embryos_selected" class="form-control" placeholder="e.g. 1x Day 5 Blastocyst (4AA)" value="1x Day 5 Blastocyst (4AA)" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Transfer Catheter Type</label>
                                <input type="text" name="catheter_type" class="form-control" value="Cook Sydney Soft-Pass" list="catheterList">
                                <datalist id="catheterList">
                                    <option value="Cook Sydney Soft-Pass">
                                    <option value="Wallace Classic Standard">
                                    <option value="Guardia Access Echogenic">
                                    <option value="Kitazato ET Catheter">
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ease of Transfer</label>
                                <select name="ease_of_transfer" class="form-control">
                                    <option value="Easy / Smooth">Easy / Smooth (No resistance, clean catheter tip)</option>
                                    <option value="Moderate (Tenaculum used)">Moderate (Tenaculum used)</option>
                                    <option value="Difficult (Cervical dilatation / Stylet)">Difficult (Cervical dilatation / Stylet)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Attending Physician</label>
                                <input type="text" name="doctor_name" class="form-control" value="Dr. Elena Vance, MD">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1; border-top: 1px solid var(--border-strong); padding-top: 10px; margin-top: 6px;">
                                <div style="font-weight: 700; font-size: 11px; font-family: var(--font-mono); text-transform: uppercase;">
                                    EARLY PREGNANCY OUTCOME &amp; BETA-hCG (OPTIONAL / SCHEDULED)
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Day 14 Serum Beta-hCG (mIU/mL)</label>
                                <input type="number" step="0.01" name="betahcg_day14" class="form-control" placeholder="e.g. 385.50 (Leave blank if test pending)">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Clinical Pregnancy Status</label>
                                <select name="result_status" class="form-control">
                                    <option value="">Pending (Awaiting Blood Draw)</option>
                                    <option value="Positive">Positive (Confirmed Gestational Sac)</option>
                                    <option value="Negative">Negative (&lt; 5 mIU/mL)</option>
                                    <option value="Biochemical Pregnancy">Biochemical Pregnancy</option>
                                    <option value="Clinical Miscarriage">Clinical Miscarriage</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Gestational Sacs Count (if USG positive)</label>
                                <input type="number" name="gestational_sacs" class="form-control" value="0" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newTransferModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">+ Save Transfer Record</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 7: FAILURE RCA & RE-ATTEMPTS -->
    <!-- ===================================================================== -->
    @if($activeTab === 'failures')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Cycle Failure Root Cause Analysis (RCA) &amp; Re-attempt Hub</div>
                    <div class="panel-desc">Evidence-based post-failure debriefing, protocol modification, and comparative Cycle 1 vs Cycle 2 analytics</div>
                </div>
                <div class="panel-actions" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newFailureRcaModal')">+ Document Cycle Failure &amp; RCA</button>
                </div>
            </div>

            @foreach($failures as $fail)
                <div style="border-bottom: 2px solid var(--border-strong); padding: 1.5rem; background: #ffffff;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <div style="font-size: 15px; font-weight: 700;">
                                Couple: {{ $fail->cycle->couple->wife->first_name }} & {{ $fail->cycle->couple->husband->first_name }} Patel ({{ $fail->cycle->couple->couple_code }})
                            </div>
                            <div class="mono text-muted" style="font-size: 11.5px; margin-top: 2px;">
                                Failed Attempt: Cycle #{{ $fail->cycle->cycle_number }} (CYC-{{ $fail->cycle->id }}) | Protocol: {{ $fail->cycle->protocol_name }}
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <span class="tag tag-black">FAILURE CLASSIFICATION: {{ $fail->failure_stage }}</span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editRcaModal_{{ $fail->id }}')">Edit RCA &amp; Strategy</button>
                            <form action="{{ route('ivf.failures.delete', $fail->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove failure RCA for CYC-{{ $fail->cycle_id }}?');">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-sm">Del</button>
                            </form>
                        </div>
                    </div>

                    <!-- SIDE BY SIDE COMPARISON MATRIX -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
                        <div class="panel" style="margin: 0; border: 1px solid var(--border-strong);">
                            <div style="padding: 0.75rem 1rem; background: #f4f4f5; font-weight: 700; font-family: var(--font-mono); font-size: 11px; border-bottom: 1px solid var(--border-strong);">
                                PREVIOUS FAILED CYCLE (CYCLE #{{ $fail->cycle->cycle_number }})
                            </div>
                            <div style="padding: 1rem; font-size: 12.5px;">
                                <div class="mb-2"><strong>Protocol:</strong> {{ $fail->cycle->protocol_name }}</div>
                                <div class="mb-2"><strong>Oocytes Retrieved:</strong> 4 oocytes (Only 2 MII mature)</div>
                                <div class="mb-2"><strong>Semen Technique:</strong> Standard Swim-up (Untreated high DFI: 38%)</div>
                                <div class="mb-2"><strong>Embryo Yield:</strong> Day 3 arrest. 0 Blastocysts formed.</div>
                                <div class="mb-2"><strong>Beta-hCG:</strong> 1.2 mIU/mL (Negative)</div>
                                <div style="margin-top: 1rem; padding: 0.75rem; background: #fafafa; border: 1px solid var(--border-light); font-size: 11.5px;">
                                    <strong>Root Cause Debriefing:</strong><br>
                                    {{ $fail->root_cause_analysis }}
                                </div>
                            </div>
                        </div>

                        <div class="panel" style="margin: 0; border: 1px solid var(--border-strong);">
                            <div style="padding: 0.75rem 1rem; background: #000000; color: #ffffff; font-weight: 700; font-family: var(--font-mono); font-size: 11px; border-bottom: 1px solid var(--border-strong);">
                                RE-ATTEMPT STRATEGY & ADAPTED PROTOCOL (CYCLE #2)
                            </div>
                            <div style="padding: 1rem; font-size: 12.5px;">
                                <div class="mb-2"><strong>Target Protocol:</strong> Microdose Agonist Flare + Growth Hormone Priming</div>
                                <div class="mb-2"><strong>Advanced Andrology:</strong> Zymot Microfluidic sorting to filter out fragmented sperm</div>
                                <div class="mb-2"><strong>Lab Intervention:</strong> Calcium Ionophore A23187 oocyte activation</div>
                                <div class="mb-2"><strong>Outcome Achieved:</strong> 8 oocytes, 7 MII, 3 Grade-1 Blastocysts (4AA/4AB/3BB) frozen!</div>
                                <div style="margin-top: 1rem; padding: 0.75rem; background: #f4f4f5; border: 1px solid var(--border-strong); font-size: 11.5px;">
                                    <strong>Protocol Optimization Notes:</strong><br>
                                    {{ $fail->reattempt_strategy }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT RCA MODAL -->
                <div class="modal-overlay" id="editRcaModal_{{ $fail->id }}">
                    <div class="modal-dialog">
                        <div class="modal-header">
                            <div class="modal-title">Edit Failure Analysis & Re-attempt Strategy</div>
                            <button type="button" class="modal-close" onclick="closeModal('editRcaModal_{{ $fail->id }}')">&times;</button>
                        </div>
                        <form action="{{ route('ivf.outcomes.update', $fail->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-grid" style="padding: 0;">
                                    <div class="form-group" style="grid-column: 1 / -1;">
                                        <label class="form-label">Failure Classification *</label>
                                        <input type="text" name="failure_stage" class="form-control" value="{{ $fail->failure_stage }}" required>
                                    </div>
                                    <div class="form-group" style="grid-column: 1 / -1;">
                                        <label class="form-label">Root Cause Analysis (RCA) Notes</label>
                                        <textarea name="root_cause_analysis" class="form-control" style="min-height: 90px;">{{ $fail->root_cause_analysis }}</textarea>
                                    </div>
                                    <div class="form-group" style="grid-column: 1 / -1;">
                                        <label class="form-label">Re-attempt Strategy & Protocol Changes</label>
                                        <textarea name="reattempt_strategy" class="form-control" style="min-height: 90px;">{{ $fail->reattempt_strategy }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline" onclick="closeModal('editRcaModal_{{ $fail->id }}')">Cancel</button>
                                <button type="submit" class="btn btn-black">Save Analysis</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

            <!-- FORM TO PLAN NEW RE-ATTEMPT -->
            <div class="panel" style="margin-top: 2rem;">
                <div class="panel-header">
                    <div class="panel-title">Formulate Cycle Re-attempt / Protocol Optimization</div>
                </div>
                <form action="{{ route('ivf.reattempt.store') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Couple Selection *</label>
                            <input type="text" id="reattempt_couple_search" class="form-control mb-1" placeholder="🔍 Type wife / husband name to filter..." oninput="filterSelectOptions('reattempt_couple_select', this.value)" autocomplete="off">
                            <select name="couple_id" id="reattempt_couple_select" class="form-control" required size="3" style="height: auto; max-height: 110px; padding: 4px;">
                                @foreach($couples as $cpl)
                                    <option value="{{ $cpl->id }}" data-search="{{ strtolower(($cpl->wife->first_name ?? '') . ' ' . ($cpl->wife->last_name ?? '') . ' ' . ($cpl->husband->first_name ?? '') . ' ' . ($cpl->husband->last_name ?? '') . ' ' . $cpl->couple_code . ' ' . ($cpl->wife->phone ?? '')) }}" {{ $loop->first ? 'selected' : '' }}>
                                        {{ $cpl->couple_code }} — {{ $cpl->wife->first_name }} {{ $cpl->wife->last_name }} &amp; {{ $cpl->husband ? $cpl->husband->first_name : 'No Partner' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Reference Failed Cycle *</label>
                            <input type="text" id="reattempt_cycle_search" class="form-control mb-1" placeholder="🔍 Type cycle ID or protocol to filter..." oninput="filterSelectOptions('reattempt_cycle_select', this.value)" autocomplete="off">
                            <select name="previous_cycle_id" id="reattempt_cycle_select" class="form-control" required size="3" style="height: auto; max-height: 110px; padding: 4px;">
                                @foreach($cycles as $c)
                                    <option value="{{ $c->id }}" data-search="{{ strtolower(($c->couple->wife->first_name ?? '') . ' ' . ($c->couple->wife->last_name ?? '') . ' ' . $c->couple->couple_code . ' cyc-' . $c->id . ' ' . $c->protocol_name) }}" {{ $loop->first ? 'selected' : '' }}>
                                        CYC-{{ $c->id }} (Cycle #{{ $c->cycle_number }} - {{ $c->protocol_name }}) — {{ $c->couple->wife->first_name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cycle Type</label>
                            <select name="cycle_type" class="form-control">
                                <option value="Self-ICSI (Re-attempt)">Self-ICSI (Re-attempt)</option>
                                <option value="Self-ICSI with Zymot &amp; Ca+ Activation">Self-ICSI with Zymot &amp; Ca+ Activation</option>
                                <option value="Donor Oocyte ICSI">Donor Oocyte ICSI</option>
                                <option value="Frozen Embryo Transfer (FET)">Frozen Embryo Transfer (FET)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Protocol Strategy *</label>
                            <select name="protocol_name" class="form-control">
                                <option value="Microdose Agonist Flare Protocol + Growth Hormone">Microdose Agonist Flare Protocol + Growth Hormone</option>
                                <option value="Progestin-Primed Ovarian Stimulation (PPOS)">Progestin-Primed Ovarian Stimulation (PPOS)</option>
                                <option value="Ultra-Long Agonist Protocol (for severe Endometriosis)">Ultra-Long Agonist Protocol (for severe Endometriosis)</option>
                                <option value="HRT Frozen Embryo Transfer with ERA Timing">HRT Frozen Embryo Transfer with ERA Timing</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Planned Start Date *</label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label class="form-label">Clinical Rationale &amp; Adjuvant Changes for Re-attempt</label>
                            <textarea name="cycle_notes" class="form-control" placeholder="Specify dosage adjustments, DHEA/CoQ10 priming, semen preparation enhancements..."></textarea>
                        </div>
                    </div>
                    <div style="padding: 1rem 1.5rem; background: #f4f4f5; border-top: 1px solid var(--border-strong); text-align: right;">
                        <button type="submit" class="btn btn-black">Authorize &amp; Launch Re-attempt Cycle</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- NEW FAILURE RCA MODAL -->
        <div class="modal-overlay" id="newFailureRcaModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Document Cycle Failure &amp; Root Cause Analysis (RCA)</div>
                    <button type="button" class="modal-close" onclick="closeModal('newFailureRcaModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.failures.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Select Treatment Cycle to Classify as Failed *</label>
                                <input type="text" id="new_rca_cycle_search" class="form-control mb-1" placeholder="🔍 Type patient name, code, or cycle ID to filter..." oninput="filterSelectOptions('new_rca_cycle_select', this.value)" autocomplete="off">
                                <select name="cycle_id" id="new_rca_cycle_select" class="form-control" required size="4" style="height: auto; max-height: 140px; padding: 4px;">
                                    @foreach($cycles as $c)
                                        <option value="{{ $c->id }}" data-search="{{ strtolower(($c->couple->wife->first_name ?? '') . ' ' . ($c->couple->wife->last_name ?? '') . ' ' . ($c->couple->husband->first_name ?? '') . ' ' . ($c->couple->husband->last_name ?? '') . ' ' . $c->couple->couple_code . ' cyc-' . $c->id . ' ' . $c->protocol_name) }}" {{ $loop->first ? 'selected' : '' }}>
                                            CYC-{{ $c->id }} — {{ $c->couple->wife->first_name ?? 'Wife' }} {{ $c->couple->wife->last_name ?? '' }} ({{ $c->couple->couple_code }}) [Status: {{ str_replace('_', ' ', $c->status) }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Failure Classification / Stage *</label>
                                <select name="failure_stage" class="form-control" required>
                                    <option value="Implantation Failure (Negative Beta-hCG)">Implantation Failure (Negative Beta-hCG)</option>
                                    <option value="Poor Oocyte Yield / Empty Follicle Syndrome">Poor Oocyte Yield / Empty Follicle Syndrome</option>
                                    <option value="Total Fertilization Failure (TFF)">Total Fertilization Failure (TFF)</option>
                                    <option value="Day 3 Embryo Cleavage Arrest">Day 3 Embryo Cleavage Arrest</option>
                                    <option value="Blastulation Arrest (0 Blastocysts)">Blastulation Arrest (0 Blastocysts)</option>
                                    <option value="Biochemical Pregnancy Loss">Biochemical Pregnancy Loss</option>
                                    <option value="Early Clinical Miscarriage (< 8 Weeks)">Early Clinical Miscarriage (&lt; 8 Weeks)</option>
                                </select>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Root Cause Analysis (RCA) Debriefing Notes *</label>
                                <textarea name="root_cause_analysis" class="form-control" rows="3" placeholder="Document clinical breakdown: e.g. High sperm DFI (38%) caused paternal genome arrest at day 3; thin endometrium (6.2mm); poor ovarian response..." required></textarea>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Planned Re-attempt Strategy &amp; Protocol Adjustments</label>
                                <textarea name="reattempt_strategy" class="form-control" rows="3" placeholder="Target protocol changes: e.g. Switch from Antagonist to Microdose Agonist Flare, add Zymot microfluidic chip, AOA calcium ionophore activation, DHEA priming..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newFailureRcaModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">+ Save Failure RCA</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 8: ANTENATAL CARE (ANC) -->
    <!-- ===================================================================== -->
    @if($activeTab === 'antenatal')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Antenatal Care (ANC) &amp; High-Risk Obstetric Monitoring</div>
                    <div class="panel-desc">Handover to obstetrics, EDD calibrated from embryo transfer date, NT scan, and anomaly surveillance</div>
                </div>
                <div class="panel-actions" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <input type="text" id="ancSearchInput" class="form-control" style="width: 220px; font-size: 12px; padding: 5px 10px;" placeholder="🔍 Filter ANC records..." onkeyup="filterTable('ancTableBody', this.value)" autocomplete="off">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newAncModal')">+ Register New ANC Record</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>ANC Record</th>
                            <th>Mother</th>
                            <th>EDD (Due Date)</th>
                            <th>Gravida / Para</th>
                            <th>Risk Stratification</th>
                            <th>1st Trimester NT Scan</th>
                            <th>2nd Trimester Anomaly Scan</th>
                            <th>Obstetrician</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ancTableBody">
                        @foreach($antenatals as $anc)
                            <tr>
                                <td class="mono font-bold">ANC-{{ $anc->id }}</td>
                                <td>
                                    <strong>{{ $anc->couple->wife->first_name ?? 'Mother' }} {{ $anc->couple->wife->last_name ?? '' }}</strong>
                                    <div class="mono text-muted" style="font-size: 10.5px;">Cycle: CYC-{{ $anc->cycle_id }} ({{ $anc->couple->couple_code ?? '' }})</div>
                                </td>
                                <td class="mono font-bold">{{ $anc->edd }}</td>
                                <td class="mono">G{{ $anc->gravida }} P{{ $anc->para }}</td>
                                <td><span class="tag tag-outline">{{ $anc->risk_classification }}</span></td>
                                <td>{{ $anc->nt_scan_result ?? 'Scheduled' }}</td>
                                <td>{{ $anc->anomaly_scan_result ?? 'Scheduled' }}</td>
                                <td>{{ $anc->obstetrician_name }}</td>
                                <td><span class="tag tag-black">{{ $anc->pregnancy_status }}</span></td>
                                <td class="text-right" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editAncModal_{{ $anc->id }}')">Edit ANC</button>
                                    <form action="{{ route('ivf.anc.delete', $anc->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove ANC record ANC-{{ $anc->id }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Del</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- EDIT ANC MODAL -->
                            <div class="modal-overlay" id="editAncModal_{{ $anc->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Antenatal Record: ANC-{{ $anc->id }}</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editAncModal_{{ $anc->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.anc.update', $anc->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Expected Date of Delivery (EDD) *</label>
                                                    <input type="date" name="edd" class="form-control" value="{{ $anc->edd }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Risk Classification *</label>
                                                    <input type="text" name="risk_classification" class="form-control" value="{{ $anc->risk_classification }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">1st Trimester NT Scan</label>
                                                    <input type="text" name="nt_scan_result" class="form-control" value="{{ $anc->nt_scan_result }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">2nd Trimester Anomaly Scan</label>
                                                    <input type="text" name="anomaly_scan_result" class="form-control" value="{{ $anc->anomaly_scan_result }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Attending Obstetrician</label>
                                                    <input type="text" name="obstetrician_name" class="form-control" value="{{ $anc->obstetrician_name }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Pregnancy Status</label>
                                                    <select name="pregnancy_status" class="form-control">
                                                        <option value="Ongoing" {{ $anc->pregnancy_status === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                        <option value="Delivered" {{ $anc->pregnancy_status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                        <option value="Complication" {{ $anc->pregnancy_status === 'Complication' ? 'selected' : '' }}>Complication</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editAncModal_{{ $anc->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Save ANC Record</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- NEW ANC RECORD MODAL -->
        <div class="modal-overlay" id="newAncModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Register Antenatal Care (ANC) Record</div>
                    <button type="button" class="modal-close" onclick="closeModal('newAncModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.anc.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Select Conception IVF Cycle *</label>
                                <input type="text" id="new_anc_cycle_search" class="form-control mb-1" placeholder="🔍 Type patient name, code, or cycle ID to filter..." oninput="filterSelectOptions('new_anc_cycle_select', this.value)" autocomplete="off">
                                <select name="cycle_id" id="new_anc_cycle_select" class="form-control" required size="4" style="height: auto; max-height: 140px; padding: 4px;">
                                    @foreach($cycles as $c)
                                        <option value="{{ $c->id }}" data-search="{{ strtolower(($c->couple->wife->first_name ?? '') . ' ' . ($c->couple->wife->last_name ?? '') . ' ' . ($c->couple->husband->first_name ?? '') . ' ' . ($c->couple->couple_code ?? '') . ' cyc-' . $c->id) }}">
                                            CYC-{{ $c->id }} &mdash; {{ $c->couple->wife->first_name ?? 'Mother' }} &amp; {{ $c->couple->husband->first_name ?? '' }} ({{ $c->couple->couple_code }}) [Status: {{ str_replace('_', ' ', $c->status) }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Expected Date of Delivery (EDD) *</label>
                                <input type="date" name="edd" class="form-control" value="{{ date('Y-m-d', strtotime('+266 days')) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Obstetric History (Gravida / Para) *</label>
                                <div style="display: flex; gap: 8px;">
                                    <div style="flex: 1;">
                                        <label style="font-size: 10px; color: #71717a;">Gravida (G)</label>
                                        <input type="number" name="gravida" class="form-control" value="1" min="1" required>
                                    </div>
                                    <div style="flex: 1;">
                                        <label style="font-size: 10px; color: #71717a;">Para (P)</label>
                                        <input type="number" name="para" class="form-control" value="0" min="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Risk Classification *</label>
                                <input type="text" name="risk_classification" list="riskClassificationList" class="form-control" placeholder="e.g. High-Risk IVF Conception (Advanced Maternal Age, GDM)" value="High-Risk IVF Conception" required>
                                <datalist id="riskClassificationList">
                                    <option value="High-Risk IVF Conception (Advanced Maternal Age)">
                                    <option value="Standard IVF Conception (Low Risk)">
                                    <option value="Twin Gestation (DCDA)">
                                    <option value="IVF Post-Recurrent Implantation Failure">
                                    <option value="Gestational Diabetes Mellitus (GDM)">
                                    <option value="Pregnancy-Induced Hypertension (PIH)">
                                </datalist>
                            </div>

                            <div class="form-group">
                                <label class="form-label">1st Trimester NT Scan</label>
                                <input type="text" name="nt_scan_result" class="form-control" placeholder="e.g. NT 1.2mm, Nasal Bone Present (Normal)">
                            </div>
                            <div class="form-group">
                                <label class="form-label">2nd Trimester Anomaly Scan</label>
                                <input type="text" name="anomaly_scan_result" class="form-control" placeholder="e.g. No structural defects detected, Normal liquor">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Attending Obstetrician *</label>
                                <input type="text" name="obstetrician_name" class="form-control" value="Dr. Rachel Morgan, OB/GYN" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pregnancy Status *</label>
                                <select name="pregnancy_status" class="form-control" required>
                                    <option value="Ongoing" selected>Ongoing</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Complication">Complication</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newAncModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">+ Register ANC Record</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 9: LABOR & DELIVERY -->
    <!-- ===================================================================== -->
    @if($activeTab === 'deliveries')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Labor Room, Delivery Documentation & Birth Registry</div>
                    <div class="panel-desc">Surgical and vaginal delivery logs, APGAR scores, maternal-fetal records</div>
                </div>
                <div class="panel-actions">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newDeliveryModal')">+ Register Delivery Record</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>Delivery ID</th>
                            <th>Mother Name</th>
                            <th>Delivery Date & Time</th>
                            <th>Mode of Delivery</th>
                            <th>Clinical Indication</th>
                            <th>Maternal Outcome</th>
                            <th>Delivering Surgeon</th>
                            <th>Baby Registered</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $del)
                            <tr>
                                <td class="mono font-bold">DEL-{{ $del->id }}</td>
                                <td>
                                    <strong>{{ $del->antenatalRecord->couple->wife->first_name }} {{ $del->antenatalRecord->couple->wife->last_name }}</strong>
                                </td>
                                <td class="mono">{{ $del->delivery_datetime }}</td>
                                <td><span class="tag tag-black">{{ $del->mode_of_delivery }}</span></td>
                                <td>{{ $del->indication_notes }}</td>
                                <td>{{ $del->maternal_complications ?? 'Normal, uneventful recovery' }}</td>
                                <td>{{ $del->delivering_surgeon }}</td>
                                <td>
                                    @foreach($del->babies as $b)
                                        <div class="mono"><strong>{{ $b->baby_name }}</strong> ({{ $b->baby_code }})</div>
                                        <div class="text-muted" style="font-size: 10.5px;">Weight: {{ $b->birth_weight_grams }}g | APGAR: {{ $b->apgar_1min }}/{{ $b->apgar_5min }}</div>
                                    @endforeach
                                </td>
                                <td class="text-right" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editDeliveryModal_{{ $del->id }}')">Edit Delivery</button>
                                    <form action="{{ route('ivf.deliveries.delete', $del->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove delivery record DEL-{{ $del->id }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Del</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- EDIT DELIVERY MODAL -->
                            <div class="modal-overlay" id="editDeliveryModal_{{ $del->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Labor & Delivery: DEL-{{ $del->id }}</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editDeliveryModal_{{ $del->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.deliveries.update', $del->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Delivery Date & Time *</label>
                                                    <input type="text" name="delivery_datetime" class="form-control" value="{{ $del->delivery_datetime }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Mode of Delivery *</label>
                                                    <select name="mode_of_delivery" class="form-control">
                                                        <option value="Elective LSCS (Cesarean)" {{ $del->mode_of_delivery === 'Elective LSCS (Cesarean)' ? 'selected' : '' }}>Elective LSCS (Cesarean)</option>
                                                        <option value="Emergency LSCS" {{ $del->mode_of_delivery === 'Emergency LSCS' ? 'selected' : '' }}>Emergency LSCS</option>
                                                        <option value="Normal Vaginal Delivery" {{ $del->mode_of_delivery === 'Normal Vaginal Delivery' ? 'selected' : '' }}>Normal Vaginal Delivery</option>
                                                        <option value="Assisted Instrumental" {{ $del->mode_of_delivery === 'Assisted Instrumental' ? 'selected' : '' }}>Assisted Instrumental</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" style="grid-column: 1 / -1;">
                                                    <label class="form-label">Clinical Indication Notes</label>
                                                    <input type="text" name="indication_notes" class="form-control" value="{{ $del->indication_notes }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Delivering Surgeon</label>
                                                    <input type="text" name="delivering_surgeon" class="form-control" value="{{ $del->delivering_surgeon }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editDeliveryModal_{{ $del->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Save Delivery Record</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- NEW DELIVERY MODAL -->
        <div class="modal-overlay" id="newDeliveryModal">
            <div class="modal-dialog" style="max-width: 680px;">
                <div class="modal-header">
                    <div class="modal-title">Register Labor Room &amp; Delivery Documentation</div>
                    <button type="button" class="modal-close" onclick="closeModal('newDeliveryModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.deliveries.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Select Antenatal Mother Record *</label>
                                <input type="text" id="new_del_mother_search" class="form-control mb-1" placeholder="🔍 Type mother name, couple code, or ANC ID to filter..." oninput="filterSelectOptions('new_del_mother_select', this.value)" autocomplete="off">
                                <select name="antenatal_record_id" id="new_del_mother_select" class="form-control" required size="3" style="height: auto; max-height: 120px; padding: 4px;">
                                    @foreach($antenatals as $a)
                                        <option value="{{ $a->id }}" data-search="{{ strtolower(($a->couple->wife->first_name ?? '') . ' ' . ($a->couple->wife->last_name ?? '') . ' ' . ($a->couple->couple_code ?? '') . ' anc-' . $a->id) }}">
                                            ANC-{{ $a->id }} &mdash; {{ $a->couple->wife->first_name ?? 'Mother' }} {{ $a->couple->wife->last_name ?? '' }} ({{ $a->couple->couple_code ?? '' }}) [EDD: {{ $a->edd }} | Status: {{ $a->pregnancy_status }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Delivery Date &amp; Time *</label>
                                <input type="text" name="delivery_datetime" class="form-control" value="{{ date('Y-m-d H:i') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mode of Delivery *</label>
                                <select name="mode_of_delivery" class="form-control" required>
                                    <option value="Elective LSCS (Cesarean)" selected>Elective LSCS (Cesarean)</option>
                                    <option value="Emergency LSCS">Emergency LSCS</option>
                                    <option value="Normal Vaginal Delivery">Normal Vaginal Delivery</option>
                                    <option value="Assisted Instrumental">Assisted Instrumental</option>
                                </select>
                            </div>

                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Clinical Indication Notes</label>
                                <input type="text" name="indication_notes" class="form-control" placeholder="e.g. Previous LSCS with Cephalopelvic Disproportion (CPD)">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Maternal Outcome &amp; Recovery</label>
                                <input type="text" name="maternal_complications" class="form-control" placeholder="e.g. Normal, uneventful recovery" value="Normal, uneventful recovery">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Delivering Obstetric Surgeon *</label>
                                <input type="text" name="delivering_surgeon" class="form-control" value="Dr. Rachel Morgan, OB/GYN" required>
                            </div>

                            <!-- NEWBORN BIRTH REGISTRATION -->
                            <div style="grid-column: 1 / -1; margin-top: 10px; padding: 10px; border: 1px solid var(--border-strong); background: #fafafa;">
                                <div style="font-weight: 700; font-size: 11px; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.05em;">
                                    👶 Newborn Birth Registry &amp; Pediatric Handover (Optional)
                                </div>
                                <div class="form-grid" style="padding: 0;">
                                    <div class="form-group">
                                        <label class="form-label">Baby Name / Label</label>
                                        <input type="text" name="baby_name" class="form-control" placeholder="e.g. Baby of Sophia / Aria">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Gender</label>
                                        <select name="baby_gender" class="form-control">
                                            <option value="Female">Female</option>
                                            <option value="Male">Male</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Birth Weight (Grams)</label>
                                        <input type="number" name="birth_weight_grams" class="form-control" placeholder="e.g. 3200" value="3150" min="500" max="7000">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">APGAR Score (1 min / 5 min)</label>
                                        <div style="display: flex; gap: 8px;">
                                            <input type="number" name="apgar_1min" class="form-control" placeholder="1 min" value="9" min="0" max="10">
                                            <input type="number" name="apgar_5min" class="form-control" placeholder="5 min" value="10" min="0" max="10">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Attending Pediatrician</label>
                                        <input type="text" name="pediatrician_name" class="form-control" value="Dr. David Kim, MD (Pediatrics)">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Neonatal Notes</label>
                                        <input type="text" name="discharge_notes" class="form-control" value="Cried vigorously at birth. Normal tone and reflexes.">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newDeliveryModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">+ Save Delivery &amp; Newborn</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 10: PEDIATRICS & VACCINATIONS -->
    <!-- ===================================================================== -->
    @if($activeTab === 'pediatrics')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Pediatric Care & Comprehensive Vaccination Scheduler</div>
                    <div class="panel-desc">Dynamic immunization timetable from birth to 15 months: edit records, administer, and schedule new vaccines</div>
                </div>
                <div class="panel-actions">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newVaccineModal')">+ Schedule New Vaccine</button>
                </div>
            </div>

            @foreach($babies as $bby)
                <div style="padding: 1.5rem; border-bottom: 2px solid var(--border-strong); background: #ffffff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                        <div>
                            <div style="font-size: 16px; font-weight: 700;">
                                {{ $bby->baby_name }} ({{ $bby->gender }})
                            </div>
                            <div class="mono text-muted" style="font-size: 11.5px; margin-top: 3px;">
                                Newborn ID: {{ $bby->baby_code }} | Mother: {{ $bby->deliveryRecord->antenatalRecord->couple->wife->first_name }} {{ $bby->deliveryRecord->antenatalRecord->couple->wife->last_name }} | Birth Weight: {{ $bby->birth_weight_grams }} grams | APGAR: {{ $bby->apgar_1min }}/{{ $bby->apgar_5min }}
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <span class="tag tag-black">ATTENDING: {{ $bby->pediatrician_name }}</span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editBabyModal_{{ $bby->id }}')">Edit Newborn Vitals</button>
                        </div>
                    </div>

                    <!-- EDIT BABY MODAL -->
                    <div class="modal-overlay" id="editBabyModal_{{ $bby->id }}">
                        <div class="modal-dialog">
                            <div class="modal-header">
                                <div class="modal-title">Edit Newborn Record: {{ $bby->baby_code }}</div>
                                <button type="button" class="modal-close" onclick="closeModal('editBabyModal_{{ $bby->id }}')">&times;</button>
                            </div>
                            <form action="{{ route('ivf.babies.update', $bby->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-grid" style="padding: 0;">
                                        <div class="form-group">
                                            <label class="form-label">Baby Name *</label>
                                            <input type="text" name="baby_name" class="form-control" value="{{ $bby->baby_name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Gender</label>
                                            <select name="gender" class="form-control">
                                                <option value="Female" {{ $bby->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Male" {{ $bby->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Birth Weight (Grams)</label>
                                            <input type="number" name="birth_weight_grams" class="form-control" value="{{ $bby->birth_weight_grams }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">APGAR Score (1 Min)</label>
                                            <input type="number" name="apgar_1min" class="form-control" value="{{ $bby->apgar_1min }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">APGAR Score (5 Min)</label>
                                            <input type="number" name="apgar_5min" class="form-control" value="{{ $bby->apgar_5min }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Pediatrician Name</label>
                                            <input type="text" name="pediatrician_name" class="form-control" value="{{ $bby->pediatrician_name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" onclick="closeModal('editBabyModal_{{ $bby->id }}')">Cancel</button>
                                    <button type="submit" class="btn btn-black">Save Vitals</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="clinical-table">
                            <thead>
                                <tr>
                                    <th>Milestone</th>
                                    <th>Vaccine &amp; Route</th>
                                    <th>Due Date</th>
                                    <th>Administered</th>
                                    <th>Batch / Manufacturer</th>
                                    <th>Pre-Shot Vitals</th>
                                    <th>Clinical Remarks</th>
                                    <th>Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bby->vaccinations as $v)
                                    <tr>
                                        <td class="mono font-bold">{{ $v->milestone_stage }}</td>
                                        <td>
                                            <strong>{{ $v->vaccine_name }}</strong>
                                            @if($v->site_and_route)
                                                <div class="text-muted" style="font-size: 10.5px;">{{ $v->site_and_route }}</div>
                                            @endif
                                        </td>
                                        <td class="mono">{{ $v->due_date }}</td>
                                        <td>
                                            @if($v->administered_date)
                                                <div class="mono font-bold">{{ $v->administered_date }}</div>
                                                <div class="text-muted" style="font-size: 10.5px;">By: {{ $v->administered_by ?? 'Clinician' }}</div>
                                            @else
                                                <span class="text-muted">&mdash;</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($v->batch_number)
                                                <div class="mono font-bold">{{ $v->batch_number }}</div>
                                                @if($v->manufacturer)
                                                    <div class="text-muted" style="font-size: 10.5px;">{{ $v->manufacturer }}</div>
                                                @endif
                                            @else
                                                <span class="text-muted">&mdash;</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($v->baby_weight_at_vac || $v->temperature_f)
                                                <div class="mono" style="font-size: 11px;">
                                                    @if($v->baby_weight_at_vac) Wt: {{ $v->baby_weight_at_vac }} @endif
                                                    @if($v->temperature_f) | Temp: {{ $v->temperature_f }} @endif
                                                </div>
                                            @else
                                                <span class="text-muted">&mdash;</span>
                                            @endif
                                        </td>
                                        <td style="max-width: 220px; white-space: normal; font-size: 11px; line-height: 1.35;">
                                            @if($v->clinical_remarks)
                                                {{ $v->clinical_remarks }}
                                            @else
                                                <span class="text-muted">&mdash;</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="tag {{ $v->status === 'Administered' ? 'tag-black' : 'tag-outline' }}">
                                                {{ $v->status }}
                                            </span>
                                        </td>
                                        <td class="text-right" style="white-space: nowrap;">
                                            @if($v->status !== 'Administered')
                                                <button type="button" class="btn btn-black btn-sm" onclick="openModal('administerVacModal_{{ $v->id }}')">Administer</button>
                                            @else
                                                <button type="button" class="btn btn-outline btn-sm" onclick="openModal('administerVacModal_{{ $v->id }}')">Remarks</button>
                                                <form action="{{ route('ivf.vaccines.update', $v->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Revert {{ $v->vaccine_name }} back to Scheduled status?');">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Scheduled">
                                                    <button type="submit" class="btn btn-outline btn-sm">Reset</button>
                                                </form>
                                            @endif
                                            <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editVacModal_{{ $v->id }}')">Edit</button>
                                            <form action="{{ route('ivf.vaccines.delete', $v->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove vaccine {{ $v->vaccine_name }}?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline btn-sm">Del</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- ADMINISTER VACCINE MODAL (TAKE MORE RECORDS / REMARKS) -->
                                    <div class="modal-overlay" id="administerVacModal_{{ $v->id }}">
                                        <div class="modal-dialog" style="max-width: 650px;">
                                            <div class="modal-header">
                                                <div>
                                                    <div class="modal-title">Administer Vaccine: {{ $v->vaccine_name }}</div>
                                                    <div class="text-muted" style="font-size: 11px;">Milestone: {{ $v->milestone_stage }} | Baby: {{ $bby->baby_name }} ({{ $bby->baby_code }})</div>
                                                </div>
                                                <button type="button" class="modal-close" onclick="closeModal('administerVacModal_{{ $v->id }}')">&times;</button>
                                            </div>
                                            <form action="{{ route('ivf.vaccines.update', $v->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Administered">
                                                <div class="modal-body">
                                                    <div class="form-grid" style="padding: 0;">
                                                        <div class="form-group">
                                                            <label class="form-label">Administration Date *</label>
                                                            <input type="date" name="administered_date" class="form-control" value="{{ $v->administered_date ?? date('Y-m-d') }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Batch / Lot Number *</label>
                                                            <input type="text" name="batch_number" class="form-control" value="{{ $v->batch_number ?? ('BAT-' . date('Y') . '-' . strtoupper(substr(md5($v->id . time()), 0, 5))) }}" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="form-label">Vaccine Manufacturer</label>
                                                            <input type="text" name="manufacturer" list="mfgList_{{ $v->id }}" class="form-control" placeholder="e.g. Serum Institute of India" value="{{ $v->manufacturer ?? 'Serum Institute of India' }}">
                                                            <datalist id="mfgList_{{ $v->id }}">
                                                                <option value="Serum Institute of India">
                                                                <option value="Bharat Biotech">
                                                                <option value="GlaxoSmithKline (GSK)">
                                                                <option value="Sanofi Pasteur">
                                                                <option value="Pfizer">
                                                                <option value="Biological E. Limited">
                                                            </datalist>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Vaccine Expiry Date</label>
                                                            <input type="date" name="vaccine_expiry_date" class="form-control" value="{{ $v->vaccine_expiry_date ?? date('Y-m-d', strtotime('+2 years')) }}">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="form-label">Anatomical Injection Site &amp; Route *</label>
                                                            <select name="site_and_route" class="form-control" required>
                                                                <option value="Left Anterolateral Thigh (IM)" {{ ($v->site_and_route ?? '') === 'Left Anterolateral Thigh (IM)' ? 'selected' : '' }}>Left Anterolateral Thigh (IM)</option>
                                                                <option value="Right Anterolateral Thigh (IM)" {{ ($v->site_and_route ?? '') === 'Right Anterolateral Thigh (IM)' ? 'selected' : '' }}>Right Anterolateral Thigh (IM)</option>
                                                                <option value="Left Deltoid Upper Arm (IM)" {{ ($v->site_and_route ?? '') === 'Left Deltoid Upper Arm (IM)' ? 'selected' : '' }}>Left Deltoid Upper Arm (IM)</option>
                                                                <option value="Right Deltoid Upper Arm (IM)" {{ ($v->site_and_route ?? '') === 'Right Deltoid Upper Arm (IM)' ? 'selected' : '' }}>Right Deltoid Upper Arm (IM)</option>
                                                                <option value="Oral Drops" {{ ($v->site_and_route ?? '') === 'Oral Drops' ? 'selected' : '' }}>Oral Drops</option>
                                                                <option value="Subcutaneous - Right Upper Arm" {{ ($v->site_and_route ?? '') === 'Subcutaneous - Right Upper Arm' ? 'selected' : '' }}>Subcutaneous - Right Upper Arm</option>
                                                                <option value="Intradermal - Left Arm" {{ ($v->site_and_route ?? '') === 'Intradermal - Left Arm' ? 'selected' : '' }}>Intradermal - Left Arm</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Administered By (Staff Name / RN) *</label>
                                                            <input type="text" name="administered_by" class="form-control" value="{{ $v->administered_by ?? 'Nurse Claire, RN' }}" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="form-label">Current Baby Weight</label>
                                                            <input type="text" name="baby_weight_at_vac" class="form-control" placeholder="e.g. 5.8 kg" value="{{ $v->baby_weight_at_vac ?? '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Baby Temperature</label>
                                                            <input type="text" name="temperature_f" class="form-control" placeholder="e.g. 98.4°F (Afebrile)" value="{{ $v->temperature_f ?? '98.4°F' }}">
                                                        </div>

                                                        <div class="form-group" style="grid-column: 1 / -1;">
                                                            <label class="form-label">Post-Shot Clinical Remarks &amp; Adverse Reaction Surveillance *</label>
                                                            <textarea name="clinical_remarks" class="form-control" rows="3" placeholder="Document clinical observation, reaction status, and parental advice..." required>{{ $v->clinical_remarks ?? 'Observed for 30 minutes in pediatric clinic. Cried minimally upon injection. No acute adverse reaction, rash, or wheezing observed. Parent advised on fever management and Paracetamol SOS.' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline" onclick="closeModal('administerVacModal_{{ $v->id }}')">Cancel</button>
                                                    <button type="submit" class="btn btn-black">&#x2713; Save &amp; Mark Administered</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- EDIT VACCINE MODAL -->
                                    <div class="modal-overlay" id="editVacModal_{{ $v->id }}">
                                        <div class="modal-dialog" style="max-width: 650px;">
                                            <div class="modal-header">
                                                <div class="modal-title">Edit Vaccine: {{ $v->vaccine_name }}</div>
                                                <button type="button" class="modal-close" onclick="closeModal('editVacModal_{{ $v->id }}')">&times;</button>
                                            </div>
                                            <form action="{{ route('ivf.vaccines.update_details', $v->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-grid" style="padding: 0;">
                                                        <div class="form-group">
                                                            <label class="form-label">Vaccine Name *</label>
                                                            <input type="text" name="vaccine_name" class="form-control" value="{{ $v->vaccine_name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Milestone Age *</label>
                                                            <input type="text" name="milestone_stage" class="form-control" value="{{ $v->milestone_stage }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Due Date *</label>
                                                            <input type="date" name="due_date" class="form-control" value="{{ $v->due_date }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Administered Date</label>
                                                            <input type="date" name="administered_date" class="form-control" value="{{ $v->administered_date }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Batch / Lot Number</label>
                                                            <input type="text" name="batch_number" class="form-control" value="{{ $v->batch_number }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-control">
                                                                <option value="Scheduled" {{ $v->status === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                                <option value="Administered" {{ $v->status === 'Administered' ? 'selected' : '' }}>Administered</option>
                                                                <option value="Overdue" {{ $v->status === 'Overdue' ? 'selected' : '' }}>Overdue</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Site &amp; Route</label>
                                                            <input type="text" name="site_and_route" class="form-control" value="{{ $v->site_and_route }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Administered By</label>
                                                            <input type="text" name="administered_by" class="form-control" value="{{ $v->administered_by }}">
                                                        </div>
                                                        <div class="form-group" style="grid-column: 1 / -1;">
                                                            <label class="form-label">Clinical Remarks &amp; Observations</label>
                                                            <textarea name="clinical_remarks" class="form-control" rows="2">{{ $v->clinical_remarks }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline" onclick="closeModal('editVacModal_{{ $v->id }}')">Cancel</button>
                                                    <button type="submit" class="btn btn-black">Save Vaccine</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- NEW VACCINE MULTI-SCHEDULER MODAL -->
        <div class="modal-overlay" id="newVaccineModal">
            <div class="modal-dialog" style="max-width: 760px;">
                <div class="modal-header">
                    <div>
                        <div class="modal-title">Schedule Pediatric Vaccines (Single or Multi-Dose Batch)</div>
                        <div class="text-muted" style="font-size: 11px;">Schedule individual vaccines or bundle full milestone immunization visits at once</div>
                    </div>
                    <button type="button" class="modal-close" onclick="closeModal('newVaccineModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.vaccines.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0; margin-bottom: 12px;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Select Registered Newborn Patient *</label>
                                <select name="baby_record_id" class="form-control" required>
                                    @foreach($babies as $b)
                                        <option value="{{ $b->id }}">{{ $b->baby_name }} ({{ $b->baby_code }} &mdash; Mother: {{ $b->deliveryRecord->antenatalRecord->couple->wife->first_name ?? '' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- SCHEDULING MODE SWITCHER -->
                        <div style="display: flex; gap: 8px; border-bottom: 1px solid var(--border-strong); padding-bottom: 10px; margin-bottom: 15px;">
                            <button type="button" id="tabModeBundleBtn" class="btn btn-black btn-sm" onclick="switchVacScheduleMode('bundle')">Milestone Package (Batch Schedule)</button>
                            <button type="button" id="tabModeCustomBtn" class="btn btn-outline btn-sm" onclick="switchVacScheduleMode('custom')">Custom Multi-Row List</button>
                        </div>

                        <!-- MODE 1: MILESTONE BUNDLE PRESETS -->
                        <div id="vacModeBundle">
                            <div class="form-grid" style="padding: 0;">
                                <div class="form-group">
                                    <label class="form-label">Select Milestone Stage *</label>
                                    <select id="bundleMilestoneSelect" name="milestone_stage" class="form-control" onchange="renderMilestoneVaccineCheckboxes(this.value)">
                                        <option value="6 Weeks">6 Weeks Milestone (5 Vaccines)</option>
                                        <option value="10 Weeks">10 Weeks Milestone (4 Vaccines)</option>
                                        <option value="14 Weeks">14 Weeks Milestone (5 Vaccines)</option>
                                        <option value="6 Months">6 Months Milestone (2 Vaccines)</option>
                                        <option value="9 Months">9 Months Milestone (3 Vaccines)</option>
                                        <option value="12 Months">12 Months Milestone (2 Vaccines)</option>
                                        <option value="15 Months">15 Months Milestone (3 Vaccines)</option>
                                        <option value="16-18 Months">16-18 Months Milestone (3 Vaccines)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Milestone Scheduled Due Date *</label>
                                    <input type="date" id="bundleDueDate" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+42 days')) }}" required>
                                </div>
                            </div>

                            <div style="margin-top: 10px; padding: 12px; border: 1px solid var(--border-strong); background: #fafafa;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                        Select Vaccines to Include in this Milestone:
                                    </span>
                                    <span style="font-size: 11px;">
                                        <a href="javascript:void(0);" onclick="toggleAllBundleVac(true)" style="color: #000; text-decoration: underline; margin-right: 8px;">Select All</a>
                                        <a href="javascript:void(0);" onclick="toggleAllBundleVac(false)" style="color: #000; text-decoration: underline;">Clear All</a>
                                    </span>
                                </div>
                                <div id="bundleCheckboxesContainer" style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                    <!-- Populated dynamically via JS -->
                                </div>
                            </div>
                        </div>

                        <!-- MODE 2: CUSTOM MULTI-ROW BUILDER -->
                        <div id="vacModeCustom" style="display: none;">
                            <div style="margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Custom Vaccine Queue:</span>
                                <button type="button" class="btn btn-outline btn-sm" onclick="addCustomVacRow()">+ Add Another Vaccine Row</button>
                            </div>
                            <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                <table class="clinical-table" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>Vaccine Name *</th>
                                            <th>Milestone Stage *</th>
                                            <th>Due Date *</th>
                                            <th style="width: 40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="customVacRowsBody">
                                        <tr>
                                            <td><input type="text" name="vaccine_items[0][vaccine_name]" class="form-control" placeholder="e.g. Hepatitis A - Dose 1" style="font-size: 12px;"></td>
                                            <td><input type="text" name="vaccine_items[0][milestone_stage]" class="form-control" value="Routine Milestone" style="font-size: 12px;"></td>
                                            <td><input type="date" name="vaccine_items[0][due_date]" class="form-control" value="{{ date('Y-m-d') }}" style="font-size: 12px;"></td>
                                            <td class="text-right"><button type="button" class="btn btn-outline btn-sm" onclick="removeVacRow(this)">&times;</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newVaccineModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">&#x2713; Schedule Selected Vaccines</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const milestoneBundles = {
                '6 Weeks': [
                    'Pentavalent 1 (DTwP + HepB + Hib)',
                    'Oral Polio Vaccine (OPV-1)',
                    'Rotavirus Vaccine (RV-1)',
                    'Pneumococcal Conjugate Vaccine (PCV-1)',
                    'Inactivated Polio Vaccine (IPV-1)'
                ],
                '10 Weeks': [
                    'Pentavalent 2 (DTwP + HepB + Hib)',
                    'Oral Polio Vaccine (OPV-2)',
                    'Rotavirus Vaccine (RV-2)',
                    'Pneumococcal Conjugate Vaccine (PCV-2)'
                ],
                '14 Weeks': [
                    'Pentavalent 3 (DTwP + HepB + Hib)',
                    'Oral Polio Vaccine (OPV-3)',
                    'Rotavirus Vaccine (RV-3)',
                    'Pneumococcal Conjugate Vaccine (PCV-3)',
                    'Inactivated Polio Vaccine (IPV-2)'
                ],
                '6 Months': [
                    'Influenza Vaccine (Flu-1)',
                    'Typhoid Conjugate Vaccine (TCV)'
                ],
                '9 Months': [
                    'Measles, Mumps & Rubella (MMR-1)',
                    'Vitamin A (1st Dose)',
                    'Japanese Encephalitis (JE-1)'
                ],
                '12 Months': [
                    'Hepatitis A (Dose 1)',
                    'Japanese Encephalitis (JE-2)'
                ],
                '15 Months': [
                    'Measles, Mumps & Rubella (MMR-2)',
                    'Varicella (Chickenpox Dose 1)',
                    'PCV Booster'
                ],
                '16-18 Months': [
                    'DTP Booster-1 (Diphtheria, Tetanus, Pertussis)',
                    'IPV Booster',
                    'Hib Booster'
                ]
            };

            function renderMilestoneVaccineCheckboxes(milestone) {
                const container = document.getElementById('bundleCheckboxesContainer');
                if (!container) return;
                const list = milestoneBundles[milestone] || [];
                container.innerHTML = '';
                list.forEach((vac, idx) => {
                    const label = document.createElement('label');
                    label.style.display = 'flex';
                    label.style.alignItems = 'center';
                    label.style.gap = '8px';
                    label.style.fontSize = '12px';
                    label.style.cursor = 'pointer';
                    label.style.padding = '5px 8px';
                    label.style.border = '1px solid #000000';
                    label.style.background = '#ffffff';
                    label.innerHTML = `<input type="checkbox" name="preset_vaccines[]" value="${vac}" checked> <span>${vac}</span>`;
                    container.appendChild(label);
                });
            }

            function toggleAllBundleVac(check) {
                document.querySelectorAll('#bundleCheckboxesContainer input[type="checkbox"]').forEach(cb => {
                    cb.checked = check;
                });
            }

            function switchVacScheduleMode(mode) {
                const bundleDiv = document.getElementById('vacModeBundle');
                const customDiv = document.getElementById('vacModeCustom');
                const bundleBtn = document.getElementById('tabModeBundleBtn');
                const customBtn = document.getElementById('tabModeCustomBtn');

                if (mode === 'bundle') {
                    bundleDiv.style.display = '';
                    customDiv.style.display = 'none';
                    bundleBtn.className = 'btn btn-black btn-sm';
                    customBtn.className = 'btn btn-outline btn-sm';
                    document.querySelectorAll('#customVacRowsBody input').forEach(inp => inp.disabled = true);
                    document.querySelectorAll('#bundleCheckboxesContainer input').forEach(inp => inp.disabled = false);
                } else {
                    bundleDiv.style.display = 'none';
                    customDiv.style.display = '';
                    bundleBtn.className = 'btn btn-outline btn-sm';
                    customBtn.className = 'btn btn-black btn-sm';
                    document.querySelectorAll('#customVacRowsBody input').forEach(inp => inp.disabled = false);
                    document.querySelectorAll('#bundleCheckboxesContainer input').forEach(inp => inp.disabled = true);
                }
            }

            let customRowIndex = 1;
            function addCustomVacRow() {
                const tbody = document.getElementById('customVacRowsBody');
                if (!tbody) return;
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><input type="text" name="vaccine_items[${customRowIndex}][vaccine_name]" class="form-control" placeholder="e.g. Vaccine Name" style="font-size: 12px;" required></td>
                    <td><input type="text" name="vaccine_items[${customRowIndex}][milestone_stage]" class="form-control" value="Routine Milestone" style="font-size: 12px;" required></td>
                    <td><input type="date" name="vaccine_items[${customRowIndex}][due_date]" class="form-control" value="${new Date().toISOString().split('T')[0]}" style="font-size: 12px;" required></td>
                    <td class="text-right"><button type="button" class="btn btn-outline btn-sm" onclick="removeVacRow(this)">&times;</button></td>
                `;
                tbody.appendChild(tr);
                customRowIndex++;
            }

            function removeVacRow(btn) {
                const tr = btn.closest('tr');
                if (tr) tr.remove();
            }

            document.addEventListener('DOMContentLoaded', function() {
                renderMilestoneVaccineCheckboxes('6 Weeks');
                document.querySelectorAll('#customVacRowsBody input').forEach(inp => inp.disabled = true);
            });
        </script>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 11: CRYOBANK & BIO-REPOSITORY -->
    <!-- ===================================================================== -->
    @if($activeTab === 'cryobank')
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Cryobank & Bio-Repository Management</div>
                    <div class="panel-desc">Liquid Nitrogen (-196°C) Grid Traceability: Tank &rarr; Canister &rarr; Cane &rarr; Goblet &rarr; Straw</div>
                </div>
                <div class="panel-actions" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <input type="text" id="cryoSearchInput" class="form-control" style="width: 220px; font-size: 12px; padding: 5px 10px;" placeholder="🔍 Filter Cryobank records..." onkeyup="filterTable('cryoTableBody', this.value)" autocomplete="off">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newCryoModal')">+ Add Bio-Bank Specimen</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>Storage Record</th>
                            <th>Specimen Type</th>
                            <th>Couple Owner</th>
                            <th>Tank Location</th>
                            <th>Canister / Cane</th>
                            <th>Straw Color & Code</th>
                            <th>Quantity</th>
                            <th>Freeze Date</th>
                            <th>Expiry / Renewal</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cryoTableBody">
                        @foreach($cryoStorages as $cryo)
                            <tr>
                                <td class="mono font-bold">CRYO-{{ $cryo->id }}</td>
                                <td><strong>{{ $cryo->specimen_type }}</strong></td>
                                <td>
                                    <strong>{{ $cryo->couple->wife->first_name ?? 'Couple' }} {{ $cryo->couple->wife->last_name ?? '' }}</strong>
                                    <div class="mono text-muted" style="font-size: 10.5px;">
                                        {{ $cryo->couple->couple_code ?? 'CPL' }}
                                        @if($cryo->cycle_id)
                                            | Cycle: CYC-{{ $cryo->cycle_id }}
                                        @endif
                                    </div>
                                </td>
                                <td class="mono font-bold">{{ $cryo->tank_id }}</td>
                                <td class="mono">{{ $cryo->canister_id }} / {{ $cryo->cane_id }}</td>
                                <td class="mono font-bold">{{ $cryo->straw_color_code }}</td>
                                <td class="mono">{{ $cryo->quantity }} Straw(s)</td>
                                <td class="mono">{{ $cryo->freeze_date }}</td>
                                <td class="mono">{{ $cryo->expiry_date }}</td>
                                <td>
                                    <span class="tag tag-black">{{ $cryo->status }}</span>
                                </td>
                                <td class="text-right" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editCryoModal_{{ $cryo->id }}')">Edit Location</button>
                                    <form action="{{ route('ivf.cryo.delete', $cryo->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove straw record CRYO-{{ $cryo->id }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Del</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- EDIT CRYO MODAL -->
                            <div class="modal-overlay" id="editCryoModal_{{ $cryo->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Cryo Storage: CRYO-{{ $cryo->id }}</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editCryoModal_{{ $cryo->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.cryo.update', $cryo->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group">
                                                    <label class="form-label">Specimen Type *</label>
                                                    <select name="specimen_type" class="form-control">
                                                        <option value="Blastocyst Embryos" {{ $cryo->specimen_type === 'Blastocyst Embryos' ? 'selected' : '' }}>Blastocyst Embryos</option>
                                                        <option value="Cleavage Embryos" {{ $cryo->specimen_type === 'Cleavage Embryos' ? 'selected' : '' }}>Cleavage Embryos</option>
                                                        <option value="MII Oocytes" {{ $cryo->specimen_type === 'MII Oocytes' ? 'selected' : '' }}>MII Oocytes</option>
                                                        <option value="Semen Vials" {{ $cryo->specimen_type === 'Semen Vials' ? 'selected' : '' }}>Semen Vials</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Tank Identifier *</label>
                                                    <input type="text" name="tank_id" class="form-control" value="{{ $cryo->tank_id }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Canister Identifier *</label>
                                                    <input type="text" name="canister_id" class="form-control" value="{{ $cryo->canister_id }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Cane Identifier *</label>
                                                    <input type="text" name="cane_id" class="form-control" value="{{ $cryo->cane_id }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Straw Color & Label Code *</label>
                                                    <input type="text" name="straw_color_code" class="form-control" value="{{ $cryo->straw_color_code }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" name="quantity" class="form-control" value="{{ $cryo->quantity }}" min="1" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Freeze Date</label>
                                                    <input type="date" name="freeze_date" class="form-control" value="{{ $cryo->freeze_date }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Expiry / Renewal Date</label>
                                                    <input type="date" name="expiry_date" class="form-control" value="{{ $cryo->expiry_date }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Storage Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="Stored" {{ $cryo->status === 'Stored' ? 'selected' : '' }}>Stored</option>
                                                        <option value="Thawed" {{ $cryo->status === 'Thawed' ? 'selected' : '' }}>Thawed</option>
                                                        <option value="Disposed" {{ $cryo->status === 'Disposed' ? 'selected' : '' }}>Disposed</option>
                                                        <option value="Quarantined" {{ $cryo->status === 'Quarantined' ? 'selected' : '' }}>Quarantined</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editCryoModal_{{ $cryo->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Save Cryo Record</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- NEW CRYO MODAL -->
        <div class="modal-overlay" id="newCryoModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Deposit Specimen into Cryobank</div>
                    <button type="button" class="modal-close" onclick="closeModal('newCryoModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.cryo.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Select Couple Owner *</label>
                                <input type="text" id="new_cryo_couple_search" class="form-control mb-1" placeholder="🔍 Type patient name or couple code to filter..." oninput="filterSelectOptions('new_cryo_couple_select', this.value)" autocomplete="off">
                                <select name="couple_id" id="new_cryo_couple_select" class="form-control" required size="4" style="height: auto; max-height: 130px; padding: 4px;">
                                    @foreach($couples as $c)
                                        <option value="{{ $c->id }}" data-search="{{ strtolower(($c->wife->first_name ?? '') . ' ' . ($c->wife->last_name ?? '') . ' ' . ($c->husband->first_name ?? '') . ' ' . $c->couple_code) }}">
                                            {{ $c->couple_code }} &mdash; {{ $c->wife->first_name ?? 'Mother' }} &amp; {{ $c->husband->first_name ?? 'Father' }} {{ $c->wife->last_name ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Associated Treatment Cycle (Optional)</label>
                                <select name="cycle_id" class="form-control">
                                    <option value="">None / Standalone Bio-Bank</option>
                                    @foreach($cycles as $cyc)
                                        <option value="{{ $cyc->id }}">
                                            CYC-{{ $cyc->id }} &mdash; {{ $cyc->couple->wife->first_name ?? 'Mother' }} ({{ $cyc->couple->couple_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Specimen Type *</label>
                                <select name="specimen_type" class="form-control" required>
                                    <option value="Blastocyst Embryos" selected>Blastocyst Embryos</option>
                                    <option value="Cleavage Embryos">Cleavage Embryos</option>
                                    <option value="MII Oocytes">MII Oocytes</option>
                                    <option value="Semen Vials">Semen Vials</option>
                                    <option value="Testicular Biopsy (TESA / TESE)">Testicular Biopsy (TESA / TESE)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tank Identifier *</label>
                                <input type="text" name="tank_id" class="form-control" value="LN2-Tank-Alpha-1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Canister Identifier *</label>
                                <input type="text" name="canister_id" class="form-control" value="Canister-06" placeholder="e.g. Canister-06" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cane Identifier *</label>
                                <input type="text" name="cane_id" class="form-control" value="Cane-02" placeholder="e.g. Cane-02" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Straw Color &amp; Label Code *</label>
                                <input type="text" name="straw_color_code" class="form-control" value="Green-Straw-4AA" placeholder="e.g. Blue-Straw-B1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Quantity (Straws / Vials) *</label>
                                <input type="number" name="quantity" class="form-control" value="2" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Freeze Date *</label>
                                <input type="date" name="freeze_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Expiry / Annual Renewal *</label>
                                <input type="date" name="expiry_date" class="form-control" value="{{ date('Y-m-d', strtotime('+1 year')) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Storage Status *</label>
                                <select name="status" class="form-control" required>
                                    <option value="Stored" selected>Stored</option>
                                    <option value="Quarantined">Quarantined</option>
                                    <option value="Thawed">Thawed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newCryoModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">+ Store in Bio-Bank</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- TAB 12: SETTINGS & SYSTEM ADMINISTRATION -->
    <!-- ===================================================================== -->
    @if($activeTab === 'settings')
        <!-- PANEL 1: USER MANAGEMENT DIRECTORY -->
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">System Users &amp; Role-Based Access Control (RBAC)</div>
                    <div class="panel-desc">Manage clinical staff accounts, permissions, operational status, and security credentials</div>
                </div>
                <div class="panel-actions" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <input type="text" id="userSearchInput" class="form-control" style="width: 220px; font-size: 12px; padding: 5px 10px;" placeholder="🔍 Filter staff users..." onkeyup="filterTable('usersTableBody', this.value)" autocomplete="off">
                    <button type="button" class="btn btn-black btn-sm" onclick="openModal('newUserModal')">+ Create New Staff User</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th>Staff Member</th>
                            <th>Email / Login</th>
                            <th>Role / Designation</th>
                            <th>Status</th>
                            <th>Created On</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @foreach($allUsers as $u)
                            <tr>
                                <td>
                                    <strong>{{ $u->name }}</strong>
                                    @if(Auth::id() === $u->id)
                                        <span class="tag tag-outline" style="font-size: 10px; margin-left: 6px;">YOU (CURRENT USER)</span>
                                    @endif
                                </td>
                                <td class="mono font-bold">{{ $u->email }}</td>
                                <td><span class="tag tag-black">{{ $u->role ?? 'Clinical Staff' }}</span></td>
                                <td>
                                    <span class="tag {{ $u->status === 'Active' ? 'tag-black' : 'tag-outline' }}">
                                        {{ $u->status ?? 'Active' }}
                                    </span>
                                </td>
                                <td class="mono">{{ $u->created_at ? $u->created_at->format('Y-m-d') : '—' }}</td>
                                <td class="text-right" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openModal('editUserModal_{{ $u->id }}')">Edit User</button>
                                    @if(Auth::id() !== $u->id)
                                        <form action="{{ route('ivf.users.delete', $u->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove user account {{ $u->name }} ({{ $u->email }})?');">
                                            @csrf
                                            <button type="submit" class="btn btn-outline btn-sm">Del</button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-outline btn-sm" disabled style="opacity: 0.4; cursor: not-allowed;" title="Cannot delete active session">Del</button>
                                    @endif
                                </td>
                            </tr>

                            <!-- EDIT USER MODAL -->
                            <div class="modal-overlay" id="editUserModal_{{ $u->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit Staff Account: {{ $u->name }}</div>
                                        <button type="button" class="modal-close" onclick="closeModal('editUserModal_{{ $u->id }}')">&times;</button>
                                    </div>
                                    <form action="{{ route('ivf.users.update', $u->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-grid" style="padding: 0;">
                                                <div class="form-group" style="grid-column: 1 / -1;">
                                                    <label class="form-label">Full Name &amp; Clinical Title *</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Email Address (Login Username) *</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Assigned Clinical Role *</label>
                                                    <select name="role" class="form-control" required>
                                                        <option value="Super Admin / Medical Director" {{ $u->role === 'Super Admin / Medical Director' ? 'selected' : '' }}>Super Admin / Medical Director</option>
                                                        <option value="Senior Reproductive Endocrinologist" {{ $u->role === 'Senior Reproductive Endocrinologist' ? 'selected' : '' }}>Senior Reproductive Endocrinologist</option>
                                                        <option value="Chief Embryologist" {{ $u->role === 'Chief Embryologist' ? 'selected' : '' }}>Chief Embryologist</option>
                                                        <option value="Consultant Obstetrician" {{ $u->role === 'Consultant Obstetrician' ? 'selected' : '' }}>Consultant Obstetrician</option>
                                                        <option value="Consultant Pediatrician" {{ $u->role === 'Consultant Pediatrician' ? 'selected' : '' }}>Consultant Pediatrician</option>
                                                        <option value="Senior Clinical Nurse" {{ $u->role === 'Senior Clinical Nurse' ? 'selected' : '' }}>Senior Clinical Nurse</option>
                                                        <option value="Clinic Receptionist / CRM" {{ $u->role === 'Clinic Receptionist / CRM' ? 'selected' : '' }}>Clinic Receptionist / CRM</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Account Status *</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="Active" {{ $u->status === 'Active' ? 'selected' : '' }}>Active</option>
                                                        <option value="Suspended" {{ $u->status === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Reset Password (leave blank to keep current)</label>
                                                    <input type="password" name="password" class="form-control" placeholder="New password (optional)" minlength="6">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline" onclick="closeModal('editUserModal_{{ $u->id }}')">Cancel</button>
                                            <button type="submit" class="btn btn-black">Save Account</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PANEL 2: CHANGE MY PASSWORD & PROFILE SECURITY -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
            <div class="panel" style="margin: 0;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Change Account Password</div>
                        <div class="panel-desc">Update credentials for active session: {{ Auth::user()->email ?? '' }}</div>
                    </div>
                </div>
                <div style="padding: 1.5rem;">
                    <form action="{{ route('ivf.users.password') }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label">Current Password *</label>
                            <input type="password" name="current_password" class="form-control" required placeholder="Enter existing password">
                        </div>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label">New Password * (Min 6 characters)</label>
                            <input type="password" name="new_password" class="form-control" required placeholder="Enter new secure password" minlength="6">
                        </div>
                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label class="form-label">Confirm New Password *</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required placeholder="Repeat new password" minlength="6">
                        </div>
                        <button type="submit" class="btn btn-black">Update My Password &rarr;</button>
                    </form>
                </div>
            </div>

            <!-- PANEL 3: CLINIC MASTER SETTINGS & ART REGISTRY PARAMETERS -->
            <div class="panel" style="margin: 0;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Clinic Profile &amp; Regulatory Parameters</div>
                        <div class="panel-desc">National ART Registry, Embryology baselines &amp; LN2 alarms</div>
                    </div>
                </div>
                <div style="padding: 1.5rem;">
                    <form action="{{ route('ivf.settings.clinic') }}" method="POST">
                        @csrf
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Centre Legal Name</label>
                                <input type="text" name="clinic_name" class="form-control" value="AURA Reproductive Medicine &amp; Advanced Conception Centre">
                                <input type="text" name="clinic_name" class="form-control" value="{{ old('clinic_name', $clinicSetting->clinic_name ?? 'AURA Reproductive Medicine & Advanced Conception Centre') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">National ART Registry No.</label>
                                <input type="text" name="art_reg_no" class="form-control" value="ART-IND-2026-0894">
                                <input type="text" name="art_reg_no" class="form-control" value="{{ old('art_reg_no', $clinicSetting->art_reg_no ?? 'ART-IND-2026-0894') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Lead Medical Director</label>
                                <input type="text" name="director_name" class="form-control" value="Dr. Elena Vance, MD (Reprod. Endo)">
                                <input type="text" name="director_name" class="form-control" value="{{ old('director_name', $clinicSetting->director_name ?? 'Dr. Elena Vance, MD (Reprod. Endo)') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $clinicSetting->contact_phone ?? '+91 98200 00000') }}" placeholder="+91 98200 00000">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $clinicSetting->contact_email ?? 'contact@aura-ivf.com') }}" placeholder="contact@aura-ivf.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Embryo Incubator Baseline</label>
                                <input type="text" name="incubator_temp" class="form-control" value="37.0°C (6% CO2, 5% O2)">
                                <input type="text" name="incubator_temp" class="form-control" value="{{ old('incubator_temp', $clinicSetting->incubator_temp ?? '37.0°C (6% CO2, 5% O2)') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">LN2 Tank Alarm Level</label>
                                <input type="text" name="ln2_alarm" class="form-control" value="Liquid Level < 15 cm">
                                <input type="text" name="ln2_alarm" class="form-control" value="{{ old('ln2_alarm', $clinicSetting->ln2_alarm ?? 'Liquid Level < 15 cm') }}" required>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Facility Address</label>
                                <input type="text" name="clinic_address" class="form-control" value="{{ old('clinic_address', $clinicSetting->clinic_address ?? 'Suite 401, Healthcare Boulevard, Metro City, India') }}">
                            </div>
                        </div>
                        <div style="margin-top: 1rem;">
                            <button type="submit" class="btn btn-black">Save Facility Baselines</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- NEW USER MODAL -->
        <div class="modal-overlay" id="newUserModal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <div class="modal-title">Create New Staff User Account</div>
                    <button type="button" class="modal-close" onclick="closeModal('newUserModal')">&times;</button>
                </div>
                <form action="{{ route('ivf.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-grid" style="padding: 0;">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Full Name &amp; Clinical Title *</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Marcus Vance, MD" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address (Login Username) *</label>
                                <input type="email" name="email" class="form-control" placeholder="e.g. staff@aura-ivf.com" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Assigned Clinical Role *</label>
                                <select name="role" class="form-control" required>
                                    <option value="Senior Reproductive Endocrinologist">Senior Reproductive Endocrinologist</option>
                                    <option value="Chief Embryologist">Chief Embryologist</option>
                                    <option value="Consultant Obstetrician">Consultant Obstetrician</option>
                                    <option value="Consultant Pediatrician">Consultant Pediatrician</option>
                                    <option value="Senior Clinical Nurse">Senior Clinical Nurse</option>
                                    <option value="Clinic Receptionist / CRM">Clinic Receptionist / CRM</option>
                                    <option value="Super Admin / Medical Director">Super Admin / Medical Director</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Initial Password * (Min 6 chars)</label>
                                <input type="password" name="password" class="form-control" placeholder="Create strong password" required minlength="6">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required minlength="6">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label class="form-label">Account Status</label>
                                <select name="status" class="form-control">
                                    <option value="Active" selected>Active</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('newUserModal')">Cancel</button>
                        <button type="submit" class="btn btn-black">+ Provision Staff Account</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection
