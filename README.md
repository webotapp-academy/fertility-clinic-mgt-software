# AURA IVF — Reproductive Medicine & Assisted Conception Clinical OS

[![License: MIT](https://img.shields.io/badge/License-MIT-black.svg?style=flat-square)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-black.svg?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-black.svg?style=flat-square&logo=php)](https://php.net)
[![Database](https://img.shields.io/badge/Database-MySQL%20%7C%20MariaDB%20%7C%20SQLite-black.svg?style=flat-square)](https://mysql.com)
[![Design](https://img.shields.io/badge/Design%20System-Monochrome%20Clinical%20Light-black.svg?style=flat-square)](#design-system--clinical-ui)

**AURA IVF** is an enterprise-grade, open-source Clinical Operating System engineered specifically for Assisted Reproductive Technology (ART) centres, IVF hospitals, embryology laboratories, and specialized maternity networks.

From initial infertility enquiry and legal KYC compliance to follicle stimulation, embryology grading, cycle failure root-cause analysis (RCA), high-risk obstetric monitoring, labor delivery, and pediatric vaccination tracking — AURA IVF provides a unified, zero-distraction clinical workstation.

---

## 🏛 Key Clinical Modules

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          AURA IVF CLINICAL OS                               │
├─────────────────────┬───────────────────────────┬───────────────────────────┤
│    MAIN SYSTEM      │      TREATMENT & LAB      │   MATERNITY & BIO-BANK    │
├─────────────────────┼───────────────────────────┼───────────────────────────┤
│ • Overview & KPIs   │ • Folliculometry Scans    │ • Antenatal Care (ANC)    │
│ • Enquiries & CRM   │ • OPU & Embryology Lab    │ • Labor & Delivery Suite  │
│ • Couples & Workup  │ • Embryo Transfer & Beta  │ • Pediatrics & Vaccines   │
│                     │ • Failure RCA & Protocols │ • Cryobank Bio-Repository │
└─────────────────────┴───────────────────────────┴───────────────────────────┘
```

### 1. Overview & Real-Time Clinical KPIs
* Dynamic top-bar metrics tracking active CRM pipeline, verified couples, stimulation cycles underway, re-attempts managed, vitrified cryo straws, live birth graduations, and scheduled pediatric vaccine milestones.
* Action queues for imminent trigger shots (35h hCG countdown), Day 5 blastocyst inspection windows, quantitative Beta-hCG blood review, and Liquid Nitrogen (LN2) safety alarms.

### 2. Patient Relationship CRM & Infertility Enquiries
* Inbound enquiry management with primary etiology classification: Tubal Factor, Male Infertility, Diminished Ovarian Reserve (DOR), PCOS, Endometriosis, Advanced Maternal Age, or Unexplained.
* Consultation pipeline, referral source tracking, priority status, and one-click conversion into registered couples.

### 3. Couple Onboarding, Legal KYC & Pre-ART Workup
* Full female and male partner profiles with karyotyping, blood grouping, and infectious serology screening (HIV, HBsAg, HCV, VDRL).
* Strict compliance with the National Assisted Reproductive Technology (Regulation) Act & ICMR ethical guidelines.
* Baseline diagnostic logging: Anti-Müllerian Hormone (AMH), Baseline Antral Follicle Count (AFC), Semen Analysis, and Diagnostic Hysteroscopy/SIS.

### 4. Controlled Ovarian Stimulation (COS) & Folliculometry
* Flexible protocol formulation: Antagonist protocols, Agonist microdose flare, Long luteal agonist, Natural / Mild IVF, and Freeze-All strategies for OHSS prevention.
* Longitudinal ultrasound folliculometry tracking (Day 3 through Day 13+): Per-ovary follicle diameter distribution, endometrial thickness, trilaminar pattern grading, Serum Estradiol ($E_2$) & Progesterone ($P_4$) kinetics.
* Dual / Recombinant hCG trigger scheduling with exact 35-36 hour countdown timer to surgical oocyte retrieval.

### 5. Oocyte Pick-Up (OPU) & Embryology Laboratory Information System (ELIS)
* Surgical aspiration logs: Total oocytes retrieved, MII mature metaphase-II oocytes, MI, and GV germinal vesicles.
* Andrology & sperm preparation: Swim-up, density gradient centrifugation, Zymot microfluidic sorting, and donor quarantine verification.
* Insemination techniques: Conventional IVF vs. ICSI vs. IMSI vs. PICSI with Calcium Ionophore activation for previous fertilization arrest.
* Blastocyst Gardner Grading (Day 1 2PN fertilization check, Day 3 cleavage cell-count/fragmentation, Day 5/6 expanded blastocysts e.g., `4AA`, `4AB`, `3BB`).
* PGT-A / PGT-M biopsy documentation and genetics laboratory chain-of-custody tracking.

### 6. Embryo Transfers (ET) & Quantitative Beta-hCG Monitoring
* Fresh Embryo Transfer vs. Frozen Embryo Transfer (FET) under HRT programmed endometrium or natural modified cycles.
* Transfer catheter type, acoustic ultrasound guidance documentation, and retained embryo safety clearance.
* Serial quantitative Serum Beta-hCG monitoring (Day 14 and Day 16 post-transfer) with doubling-velocity validation.
* Early viability ultrasound logging (gestational sac, yolk sac, crown-rump length CRL, and fetal heart activity).

### 7. Cycle Failure Root Cause Analysis (RCA) & Smart Re-attempts
* Multi-factorial failure taxonomy: Implantation failure, embryonic arrest, poor ovarian response, premature luteinization, or fertilization failure.
* Dedicated Root Cause Analysis: Endometrial receptivity shift (ERA), immunological / thrombophilia screening, sperm DNA fragmentation index (DFI), and uterine structural factors.
* Algorithmic Re-attempt Cycle Planner: Automatically carries over cycle failure insights and suggests optimized protocols (e.g. Microdose Flare + Growth Hormone priming, PRP endometrial infusion, PICSI + Zymot).

### 8. High-Risk Antenatal Care (ANC)
* Trimester-by-trimester obstetric monitoring with Estimated Date of Delivery (EDD) calculated directly from embryo age and transfer date.
* Ultrasound biometric tracking: Biparietal Diameter (BPD), Head Circumference (HC), Abdominal Circumference (AC), Femur Length (FL), and Estimated Fetal Weight (EFW).
* High-risk maternal alerts: Gestational Diabetes Mellitus (GDM), Pre-eclampsia, Placenta Previa, and Multi-fetal IVF Gestations.

### 9. Labor Room Suite & Delivery Documentation
* Labor onset tracking, delivery mode (Spontaneous Vaginal, Vacuum-Assisted, Elective/Emergency LSCS Cesarean Section).
* Live birth registry: Gestational age at birth, infant gender, birth weight, and 1-minute & 5-minute Apgar scoring.
* Immediate pediatrician handover and post-partum maternal stabilization logs.

### 10. Pediatric Care & Multi-Dose Vaccination Scheduler
* Birth records linked directly to the parent IVF conception cycle.
* Comprehensive pediatric milestone monitoring.
* Automated immunization scheduler following WHO and National Immunization Programs (Birth, 6w, 10w, 14w, 6m, 9m, 12m, 15m, 18m).
* Batch vaccination scheduling and clinical administration remarks with adverse event surveillance.

### 11. Cryobank & Bio-Repository Management
* Liquid Nitrogen ($LN_2$) bio-repository tracking for Embryos, Oocytes, and Semen straws.
* Physical storage hierarchy: Tank ID &rarr; Canister &rarr; Goblet &rarr; Straw / Cryovial ID.
* Vitrification media lot tracking, storage duration billing, and annual cryopreservation renewal consents.

### 12. Settings, Facility Master & Role-Based Access Control (RBAC)
* National ART Registry accreditation number and Medical Director licensing.
* Environmental baselines: Embryology incubator calibration ($37.0^\circ\text{C}$, $6.0\%\text{ CO}_2$, $5.0\%\text{ O}_2$) and $LN_2$ minimum safety alarm levels.
* Role-Based Access Control restricting views to relevant clinical workflows.

---

## 🔐 Clinical Role-Based Access Control (RBAC)

AURA IVF enforces clinical specialty separation so personnel only interact with relevant modules:

| Clinical Role | Accessible Modules |
| :--- | :--- |
| **Super Admin / Medical Director** | Full Access: All 12 Modules + Staff Accounts & Master Settings |
| **Senior Reproductive Endocrinologist** | Overview, CRM, Couples, Stimulation, Transfers, Failures, Cryobank |
| **Chief Embryologist** | Overview, Stimulation, Embryology Lab, Transfers, Failures, Cryobank |
| **Consultant Obstetrician** | Overview, Couples, Antenatal Care (ANC), Labor & Delivery |
| **Consultant Pediatrician** | Overview, Labor & Delivery, Pediatrics & Vaccinations |
| **Senior Clinical Nurse** | Overview, Stimulation, Antenatal Care (ANC), Pediatrics & Vaccinations |
| **Clinic Receptionist / CRM** | Overview, Enquiries & CRM, Couples & KYC Workup |

---

## 🎨 Design System & Clinical UI

* **100% Monochrome Light Theme:** High-contrast pure white (`#ffffff`) background with deep black (`#000000`) typography and crisp 1px solid borders.
* **Ergonomic for Clinical Lighting:** Eliminates eye-strain in dimly lit embryology laser suites, ultrasound rooms, and surgical theatres.
* **Zero Color Distractions:** No colorful status badges or unnecessary visual clutter; clinical statuses are conveyed through high-legibility typographic framing.
* **Localized Clinical Standards:** Defaults to India (`+91`) mobile phone inputs and standard ISO dates (`YYYY-MM-DD`).

---

## 🚀 Quickstart & Installation

### Prerequisites
* **PHP:** >= 8.2 with `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
* **Composer:** >= 2.x
* **Database:** MySQL >= 8.0 or MariaDB >= 10.4 (or SQLite for local testing)
* **Web Server:** Apache (XAMPP / MAMP), Nginx, or built-in PHP server

### 1. Clone the Repository
```bash
git clone https://github.com/webotapp-academy/fertility-clinic-mgt-software.git
cd fertility-clinic-mgt-software
```

### 2. Install PHP Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ivf
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration & Clinical Seeding
Create the database in MySQL (`CREATE DATABASE ivf;`), then run:
```bash
php artisan migrate --seed
```

### 5. Launch Local Workstation
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000` in your web browser.

---

## 🔑 Default Clinical Accounts

The database seeder pre-configures clinical accounts for immediate evaluation:

| Role | Username / Email | Password |
| :--- | :--- | :--- |
| **Super Admin / Medical Director** | `admin@aura-ivf.com` | `admin123` |
| **Chief Embryologist** | `embryo@aura-ivf.com` | `admin123` |
| **Consultant Obstetrician** | `obgyn@aura-ivf.com` | `admin123` |
| **Senior Clinical Nurse** | `nurse@aura-ivf.com` | `admin123` |

> *Note: For production deployments, change all default passwords immediately under `Settings & Access Control`.*

---

## 📂 Project Structure

```
fertility-clinic-mgt-software/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php      # Workstation sign-in, session guard & logout
│   │   └── IvfController.php       # Core clinical operations & CRUD pipelines
│   └── Models/                     # Eloquent entities & RBAC permission logic
│       ├── AntenatalRecord.php
│       ├── BabyRecord.php
│       ├── ClinicSetting.php       # Facility baselines & ART registry singleton
│       ├── Couple.php
│       ├── CryobankStorage.php
│       ├── CycleOutcome.php
│       ├── DeliveryRecord.php
│       ├── EmbryoTransfer.php
│       ├── FollicularScan.php
│       ├── IvfCycle.php
│       ├── Lead.php
│       ├── OpuAndLabRecord.php
│       ├── User.php                # Staff accounts & canAccessTab() RBAC
│       └── VaccinationSchedule.php
├── database/
│   ├── migrations/                 # Complete relational schema
│   └── seeders/                    # Realistic clinical patient trajectories
├── resources/
│   └── views/ivf/
│       ├── layout.blade.php        # Ergonomic monochrome sidebar & top bar
│       ├── login.blade.php         # Clean login portal with 1-click clinical demos
│       └── index.blade.php         # Modular workspaces (Tabs 1 through 12)
└── routes/
    └── web.php                     # Clinical routes & authorization endpoints
```

---

## 🛡 Security & Compliance Note

AURA IVF is built with patient data security in mind:
* All patient identifiers and clinical records are protected behind authenticated sessions.
* CSRF protection is active on every state-altering form.
* Passwords are encrypted using high-cost Bcrypt hashing.
* In accordance with ART Act provisions, couples, cryo records, and cycle history maintain an immutable clinical audit trail.

---

## 📄 License

This software is open-source and licensed under the [MIT License](LICENSE). You are free to download, use, customize, and deploy it for clinical, educational, or commercial healthcare settings.

---

Developed with ❤️ for reproductive medicine professionals worldwide by [WebOtApp Academy](https://github.com/webotapp-academy).
