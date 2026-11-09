<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Leads & Inquiries
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_code')->unique();
            $table->string('wife_name');
            $table->string('husband_name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->unsignedInteger('infertility_years')->default(1);
            $table->string('infertility_type')->default('Primary'); // Primary, Secondary
            $table->string('source')->default('Walk-in'); // Website, Walk-in, Referral, Helpline, Social
            $table->string('status')->default('New'); // New, Contacted, Consult Scheduled, Converted, Dropped
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Individual Patients (Female & Male)
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('mrn')->unique(); // Medical Record Number
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['Female', 'Male']);
            $table->date('dob')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('kyc_id_type')->nullable(); // Aadhaar, Passport, National ID
            $table->string('kyc_id_number')->nullable();
            $table->timestamps();
        });

        // 3. Registered Couples
        Schema::create('couples', function (Blueprint $table) {
            $table->id();
            $table->string('couple_code')->unique();
            $table->foreignId('wife_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('husband_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->unsignedInteger('marriage_years')->default(1);
            $table->string('primary_doctor')->default('Dr. Elena Vance, MD (Reprod. Endo)');
            $table->string('counselor_name')->nullable();
            $table->enum('consent_status', ['Pending', 'Signed', 'Verified'])->default('Verified');
            $table->string('status')->default('Active'); // Active, In Treatment, Conceived, Delivered, Inactive
            $table->timestamps();
        });

        // 4. Infertility Workup & Diagnostics
        Schema::create('infertility_workups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            // Female factors
            $table->decimal('female_amh', 5, 2)->nullable(); // ng/mL
            $table->unsignedInteger('female_afc')->nullable(); // Antral Follicle Count
            $table->decimal('female_fsh', 5, 2)->nullable(); // mIU/mL
            $table->decimal('female_tsh', 5, 2)->nullable(); // uIU/mL
            $table->string('tubal_patency')->nullable(); // Both Patent, Left Blocked, Right Blocked, Both Blocked
            $table->text('uterine_cavity_notes')->nullable();
            // Male factors
            $table->decimal('semen_volume_ml', 4, 1)->nullable();
            $table->decimal('sperm_count_million', 6, 2)->nullable();
            $table->unsignedInteger('sperm_motility_percent')->nullable();
            $table->unsignedInteger('sperm_morphology_percent')->nullable();
            $table->unsignedInteger('sperm_dfi_percent')->nullable(); // DNA Fragmentation Index
            $table->string('clinical_diagnosis')->default('Unexplained Infertility');
            $table->text('doctor_recommendation')->nullable();
            $table->timestamps();
        });

        // 5. IVF Treatment Cycles (Includes Re-attempts & History)
        Schema::create('ivf_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->unsignedInteger('cycle_number')->default(1);
            $table->string('cycle_type')->default('Self-ICSI'); // Self-ICSI, Self-IVF, Donor Oocyte, FET
            $table->string('protocol_name')->default('Antagonist Protocol'); // Antagonist, Long Agonist, PPOS, Mild/Natural
            $table->boolean('is_reattempt')->default(false);
            $table->foreignId('previous_cycle_id')->nullable()->constrained('ivf_cycles')->nullOnDelete();
            $table->date('start_date');
            $table->dateTime('trigger_datetime')->nullable();
            $table->string('trigger_drug')->nullable(); // Lupron 2mg / Decapeptyl / Ovitrelle 250mcg / Dual
            $table->enum('status', [
                'Stimulation',
                'OPU_Scheduled',
                'OPU_Completed',
                'Embryo_Culture',
                'Transfer_Completed',
                'Outcome_Positive',
                'Outcome_Failed',
                'Cancelled'
            ])->default('Stimulation');
            $table->text('cycle_notes')->nullable();
            $table->timestamps();
        });

        // 6. Follicular Monitoring Scans
        Schema::create('follicular_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('ivf_cycles')->cascadeOnDelete();
            $table->date('scan_date');
            $table->unsignedInteger('cycle_day');
            $table->string('right_ovary_follicles'); // e.g. "18mm, 16mm, 15mm, 12mm"
            $table->string('left_ovary_follicles'); // e.g. "19mm, 17mm, 14mm"
            $table->decimal('endometrium_thickness_mm', 4, 1);
            $table->string('endometrium_pattern')->default('Trilaminar'); // Trilaminar, Homogenous, Hyperechoic
            $table->decimal('estradiol_e2_pg_ml', 8, 2)->nullable();
            $table->decimal('progesterone_p4_ng_ml', 6, 2)->nullable();
            $table->string('gonadotropin_dose_given')->nullable(); // e.g. "rFSH 225 IU + hMG 75 IU"
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 7. OPU & Embryology Lab Records
        Schema::create('opu_and_lab_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('ivf_cycles')->cascadeOnDelete();
            $table->date('opu_date');
            $table->unsignedInteger('oocytes_retrieved')->default(0);
            $table->unsignedInteger('mii_mature_count')->default(0);
            $table->unsignedInteger('mi_count')->default(0);
            $table->unsignedInteger('gv_count')->default(0);
            $table->string('insemination_method')->default('ICSI'); // ICSI, IVF
            $table->string('semen_prep_method')->default('Density Gradient');
            $table->decimal('post_wash_motile_count_million', 6, 2)->nullable();
            $table->unsignedInteger('fertilized_2pn_count')->default(0);
            $table->unsignedInteger('day3_cleaved_count')->default(0);
            $table->unsignedInteger('day5_blast_count')->default(0);
            $table->string('blastocyst_grades')->nullable(); // e.g. "Day 5: 4AA, 4AB, 3BB"
            $table->string('embryologist_name')->default('Dr. Sarah Chen, Senior Embryologist');
            $table->timestamps();
        });

        // 8. Embryo Transfers (Fresh ET & Frozen FET)
        Schema::create('embryo_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('ivf_cycles')->cascadeOnDelete();
            $table->enum('transfer_type', ['Fresh ET', 'Frozen Embryo Transfer (FET)'])->default('Fresh ET');
            $table->date('transfer_date');
            $table->unsignedInteger('embryos_transferred_count')->default(1);
            $table->string('embryos_selected'); // e.g. "1x Day 5 Blastocyst (4AA)"
            $table->string('catheter_type')->default('Cook Sydney Soft-Pass');
            $table->string('ease_of_transfer')->default('Easy / Smooth');
            $table->boolean('ultrasound_guided')->default(true);
            $table->string('doctor_name')->default('Dr. Elena Vance, MD');
            $table->timestamps();
        });

        // 9. Cycle Outcomes, Beta-hCG, Failure Analytics & Re-attempt Engine
        Schema::create('cycle_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('ivf_cycles')->cascadeOnDelete();
            $table->decimal('betahcg_day14', 8, 2)->nullable();
            $table->decimal('betahcg_day16', 8, 2)->nullable();
            $table->enum('result_status', ['Positive', 'Negative', 'Biochemical Pregnancy', 'Clinical Miscarriage'])->default('Negative');
            $table->unsignedInteger('gestational_sacs')->default(0);
            $table->boolean('fetal_heartbeat')->default(false);
            $table->boolean('is_failure')->default(false);
            $table->string('failure_stage')->nullable(); // OPU Low Yield, Fertilization Arrest, Blastulation Failure, Implantation Failure, Chemical Loss, Early Demise
            $table->text('root_cause_analysis')->nullable();
            $table->text('reattempt_strategy')->nullable(); // Planned modifications for Cycle 2 (Protocol switch, DFI treatment, Growth Hormone, etc.)
            $table->timestamps();
        });

        // 10. Antenatal Care (ANC) & High-Risk Obstetric Tracking
        Schema::create('antenatal_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('cycle_id')->constrained('ivf_cycles')->cascadeOnDelete();
            $table->date('edd'); // Expected Date of Delivery calibrated from ET date
            $table->unsignedInteger('gravida')->default(1);
            $table->unsignedInteger('para')->default(0);
            $table->string('risk_classification')->default('Standard IVF Conception'); // High-Risk IVF Primigravida, Twin Gestation, GDM, PIH
            $table->string('nt_scan_result')->nullable();
            $table->string('anomaly_scan_result')->nullable();
            $table->enum('pregnancy_status', ['Ongoing', 'Delivered', 'Complication'])->default('Ongoing');
            $table->string('obstetrician_name')->default('Dr. Rachel Morgan, OB/GYN');
            $table->timestamps();
        });

        // 11. Labor Room & Delivery Documentation
        Schema::create('delivery_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antenatal_record_id')->constrained('antenatal_records')->cascadeOnDelete();
            $table->dateTime('delivery_datetime');
            $table->enum('mode_of_delivery', ['Elective LSCS (Cesarean)', 'Emergency LSCS', 'Normal Vaginal Delivery', 'Assisted Instrumental']);
            $table->string('indication_notes')->nullable(); // e.g. "Previous infertility, precious pregnancy, breech presentation"
            $table->string('maternal_complications')->nullable();
            $table->string('delivering_surgeon')->default('Dr. Rachel Morgan, OB/GYN');
            $table->timestamps();
        });

        // 12. Pediatric & Newborn Record
        Schema::create('baby_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_record_id')->constrained('delivery_records')->cascadeOnDelete();
            $table->string('baby_code')->unique(); // e.g. BBY-1049-A
            $table->string('baby_name')->default('Baby of Couple');
            $table->enum('gender', ['Male', 'Female', 'Undetermined']);
            $table->unsignedInteger('birth_weight_grams'); // e.g. 3150
            $table->unsignedInteger('apgar_1min')->default(9);
            $table->unsignedInteger('apgar_5min')->default(10);
            $table->string('pediatrician_name')->default('Dr. David Kim, MD (Pediatrics)');
            $table->text('discharge_notes')->nullable();
            $table->timestamps();
        });

        // 13. Dynamic Vaccination Scheduler & Tracking
        Schema::create('vaccination_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baby_record_id')->constrained('baby_records')->cascadeOnDelete();
            $table->string('vaccine_name'); // BCG, OPV-0, Hep-B, Pentavalent, Rotavirus, PCV, MMR, etc.
            $table->string('milestone_stage'); // Birth, 6 Weeks, 10 Weeks, 14 Weeks, 6 Months, 9 Months, 12 Months, 15 Months
            $table->date('due_date');
            $table->date('administered_date')->nullable();
            $table->enum('status', ['Administered', 'Scheduled', 'Overdue'])->default('Scheduled');
            $table->string('batch_number')->nullable();
            $table->string('administered_by')->nullable();
            $table->timestamps();
        });

        // 14. Cryobank & Bio-Repository Management
        Schema::create('cryobank_storages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('cycle_id')->nullable()->constrained('ivf_cycles')->nullOnDelete();
            $table->enum('specimen_type', ['Blastocyst Embryos', 'Cleavage Embryos', 'MII Oocytes', 'Semen Vials']);
            $table->string('tank_id'); // e.g. Tank-01 (MVE CryoSystem 6000)
            $table->string('canister_id'); // e.g. Canister-03
            $table->string('cane_id'); // e.g. Cane-12
            $table->string('straw_color_code'); // e.g. White-Straw-A1
            $table->unsignedInteger('quantity')->default(1);
            $table->date('freeze_date');
            $table->date('expiry_date');
            $table->enum('status', ['Stored', 'Thawed', 'Disposed', 'Transferred'])->default('Stored');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cryobank_storages');
        Schema::dropIfExists('vaccination_schedules');
        Schema::dropIfExists('baby_records');
        Schema::dropIfExists('delivery_records');
        Schema::dropIfExists('antenatal_records');
        Schema::dropIfExists('cycle_outcomes');
        Schema::dropIfExists('embryo_transfers');
        Schema::dropIfExists('opu_and_lab_records');
        Schema::dropIfExists('follicular_scans');
        Schema::dropIfExists('ivf_cycles');
        Schema::dropIfExists('infertility_workups');
        Schema::dropIfExists('couples');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('leads');
    }
};
