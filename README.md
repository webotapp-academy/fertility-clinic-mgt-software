<div align="center">

# 🩺 AURA IVF
### Reproductive Medicine & Assisted Conception Clinical Operating System

[![Open Source Love](https://img.shields.io/badge/Open%20Source-%E2%99%A5-000000.svg?style=for-the-badge)](https://github.com/webotapp-academy/fertility-clinic-mgt-software)
[![License: MIT](https://img.shields.io/badge/License-MIT-000000.svg?style=for-the-badge)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-000000.svg?style=for-the-badge&logo=php)](https://php.net)
[![Framework](https://img.shields.io/badge/Framework-Laravel%2011-000000.svg?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Regulatory](https://img.shields.io/badge/Compliance-National%20ART%20Act%202021-000000.svg?style=for-the-badge)](#-regulatory-compliance--signed-consent-management)
[![UI System](https://img.shields.io/badge/UI-100%25%20Monochrome%20Light-000000.svg?style=for-the-badge)](#-design-system--clinical-ui)

<p align="center">
  <strong>An enterprise-grade, open-source clinical OS built specifically for Fertility Clinics, IVF Hospitals, Embryology Laboratories, and Assisted Reproductive Healthcare Networks.</strong>
</p>

[Key Features](#-clinical-architecture--key-modules) •
[RBAC Roles](#-clinical-role-based-access-control-rbac) •
[Installation Guide](#-quickstart--installation) •
[Demo Credentials](#-default-clinical-accounts) •
[Consent Archive](#-regulatory-compliance--signed-consent-management)

</div>

---

## 📖 Overview

**AURA IVF** is an end-to-end clinical management platform covering the entire patient and laboratory lifecycle in reproductive medicine. 

From initial couple consultation, legal KYC onboarding, and hormonal stimulation monitoring to surgical oocyte retrieval (OPU), embryology grading, embryo transfer, failure root-cause analysis (RCA), high-risk antenatal care (ANC), delivery documentation, and pediatric multi-vaccine tracking — AURA IVF consolidates fragmented clinical notes into one cohesive, auditable system.

---

## 🏛 Clinical Architecture & Key Modules

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

### 1. Overview & Clinical Command Center (`?tab=overview`)
* Real-time KPI counters: Enquiries in pipeline, registered couples, active ovarian stimulation cycles, failure re-attempts managed, cryobank straws stored, live birth graduations, and scheduled pediatric vaccine doses.
* Clinical benchmarks: Clinical Pregnancy Rate (CPR), 2PN Fertilization Rate, Day 5 Blastulation Rate, Single Embryo Transfer (SET) compliance, and Embryo Cryo Survival Rate.
* Urgent action queues: 35-hour hCG trigger countdown timers, Day 5 blastocyst inspection windows, Beta-hCG blood test reviews, and Liquid Nitrogen ($LN_2$) refill alerts.

### 2. Patient Relationship CRM & Infertility Enquiries (`?tab=leads`)
* Lead tracking categorized by primary infertility etiology: Tubal Factor, Male Infertility, Diminished Ovarian Reserve (DOR), PCOS, Endometriosis, Advanced Maternal Age, or Unexplained.
* Follow-up pipeline stages: `New Inquiry` &rarr; `Contacted` &rarr; `Consultation Scheduled` &rarr; `Converted to Registered Couple`.
* 1-click conversion from inquiry to official couple workup.

### 3. Couple Registration, Legal KYC & Diagnostic Workup (`?tab=couples`)
* Distinct clinical files for Female Partner (Wife) and Male Partner (Husband) with auto-generated Medical Record Numbers (MRN).
* Baseline diagnostics logging: Anti-Müllerian Hormone (AMH), Baseline Antral Follicle Count (AFC), Tubal Patency (HSG/SIS), and Semen Analysis (Sperm Count, Motility %, Kruger Morphology %, DFI %).
* Full compliance with the National Assisted Reproductive Technology (Regulation) Act & ICMR ethical guidelines.

### 4. Controlled Ovarian Stimulation (COS) & Folliculometry (`?tab=cycles`)
* Treatment protocol engine: Flexible GnRH Antagonist Protocol, Microdose Agonist Flare, Long Luteal Agonist, Mild IVF, and Freeze-All for OHSS prevention.
* Longitudinal ultrasound scan logs (Day 3 through Day 13+): Bilateral follicle size tracking (mm), endometrial thickness, trilaminar pattern grading (Grade A, B, C), and Serum Estradiol ($E_2$) kinetics.
* Dual / Recombinant hCG trigger shot scheduling with exact 35-36 hour countdown timer to surgical aspiration.

### 5. OPU Surgical Retrieval & Embryology Lab (ELIS) (`?tab=laboratory`)
* Surgical egg retrieval logs: Total aspirated oocytes, MII mature metaphase-II oocytes, MI, and GV germinal vesicles.
* Andrology semen preparation: Swim-up, density gradient centrifugation, Zymot microfluidic sorting, and donor quarantine clearance.
* Insemination methods: Conventional IVF vs. ICSI vs. IMSI vs. PICSI with Calcium Ionophore activation.
* Blastocyst Gardner Grading: Day 1 fertilization check (2PN), Day 3 cleavage cell counts and fragmentation %, and Day 5/6 expanded blastocysts (e.g., `4AA`, `4AB`, `3BB`).

### 6. Embryo Transfers (ET) & Quantitative Beta-hCG Monitoring (`?tab=transfers`)
* Fresh Embryo Transfer vs. Frozen Embryo Transfer (FET) under programmed HRT or natural modified cycles.
* Acoustic ultrasound catheter guidance documentation and retained embryo safety clearance.
* Serial quantitative Serum Beta-hCG tracking (Day 14 and Day 16 post-transfer) with doubling-velocity validation.
* Early ultrasound viability records (gestational sac, yolk sac, crown-rump length CRL, and fetal cardiac activity).

### 7. Cycle Failure Root Cause Analysis (RCA) & Smart Re-attempts (`?tab=failures`)
* Categorized failure taxonomy: Implantation failure, embryonic arrest, poor ovarian response, premature luteinization, or fertilization failure.
* Dedicated Root Cause Analysis: Endometrial receptivity shift (ERA), immunological / thrombophilia screening, sperm DNA fragmentation index (DFI), and uterine structural factors.
* Algorithmic Re-attempt Cycle Planner: Automatically carries forward cycle history and suggests optimized protocols (e.g. Microdose Flare + Growth Hormone priming, PRP endometrial infusion, PICSI + Zymot).

### 8. High-Risk Antenatal Care (ANC) (`?tab=antenatal`)
* Longitudinal obstetric monitoring with Estimated Date of Delivery (EDD) calculated directly from embryo age and transfer date.
* Ultrasound fetal biometric tracking: Biparietal Diameter (BPD), Head Circumference (HC), Abdominal Circumference (AC), Femur Length (FL), and Estimated Fetal Weight (EFW).
* High-risk pregnancy alerts: Gestational Diabetes Mellitus (GDM), Pre-eclampsia, Placenta Previa, and Multi-fetal IVF Gestations.

### 9. Labor Room Suite & Delivery Documentation (`?tab=deliveries`)
* Labor onset tracking, delivery mode (Spontaneous Vaginal, Vacuum-Assisted, Elective/Emergency LSCS Cesarean Section).
* Live birth registry: Gestational age at delivery, infant gender, birth weight, and 1-minute & 5-minute Apgar scoring.
* Pediatrician handover notes and maternal post-partum stabilization logs.

### 10. Pediatric Care & Multi-Dose Vaccination Scheduler (`?tab=pediatrics`)
* Pediatric records permanently linked to the parent IVF conception cycle.
* Milestone growth and developmental monitoring.
* Multi-dose immunization scheduler adhering to WHO and National Immunization Programs (Birth, 6w, 10w, 14w, 6m, 9m, 12m, 15m, 18m).
* Batch immunization scheduling and adverse event surveillance.

### 11. Cryobank & Bio-Repository Management (`?tab=cryobank`)
* Liquid Nitrogen ($LN_2$) bio-repository tracking for Embryos, Oocytes, and Semen straws.
* Physical storage hierarchy:
  $$\text{Tank ID} \longrightarrow \text{Canister} \longrightarrow \text{Goblet} \longrightarrow \text{Straw / Cryovial ID}$$
* Vitrification media lot tracking, storage duration billing, and annual cryopreservation renewal consents.

### 12. Clinic Master Settings & User Access Control (`?tab=settings`)
* National ART Registry accreditation number and Lead Medical Director license profile.
* Environmental baselines: Embryology incubator calibration ($37.0^\circ\text{C}$, $6.0\%\text{ CO}_2$, $5.0\%\text{ O}_2$) and $LN_2$ minimum safety alarm levels.
* User account provisioning and password management.

---

## 📑 Regulatory Compliance & Signed Consent Management

Under the National ART Act, informed consent documentation is mandatory prior to initiating clinical procedures. AURA IVF provides a complete consent lifecycle:

* **Statutory Consent Document (Form ART-04):** Built-in legal consent template featuring clinic accreditation details, couple identification, risk disclosures, cryo authorization, and advance directives.
* **Printer-Ready Format:** Clinicians can print physical consent copies directly via the built-in `Print Legal Consent Form` button.
* **Physical Scan Upload & Archive:** Once the couple signs the document, staff can upload the signed physical scan (PDF, JPG, PNG, WEBP up to 15MB).
* **Instant Verification:** Uploading attaches the document to the couple's permanent record, activates the `[FILE]` tag on the dashboard, and marks the consent status as **Verified (Signed)**.
* **Secure Access:** Documents are stored in encrypted clinical storage and can only be accessed or downloaded by authenticated medical staff.

---

## 🔐 Clinical Role-Based Access Control (RBAC)

AURA IVF enforces clinical specialty separation so medical staff only interact with modules relevant to their role:

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
* **Zero Color Distractions:** Eliminates color fatigue and optimizes visibility in low-light embryology suites and ultrasound scanning rooms.
* **Regional Clinical Localization:** Pre-configured with India (`+91`) mobile phone inputs and standard ISO date formatting (`YYYY-MM-DD`).

---

## 🚀 Quickstart & Installation

### Prerequisites
* **PHP:** >= 8.2 (Extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
* **Composer:** >= 2.x
* **Database:** MySQL >= 8.0, MariaDB >= 10.4, or SQLite
* **Web Server:** Apache (XAMPP / MAMP), Nginx, or built-in PHP server

### 1. Clone the Repository
```bash
git clone https://github.com/webotapp-academy/fertility-clinic-mgt-software.git
cd fertility-clinic-mgt-software
```

### 2. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Configure Environment
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

### 4. Run Migrations & Clinical Seeder
Create the MySQL database (`CREATE DATABASE ivf;`), then run:
```bash
php artisan migrate --seed
```

### 5. Launch Workstation
```bash
php artisan serve
```
Open your browser and navigate to: `http://127.0.0.1:8000` (or `http://localhost/sms/ivf/public/` when using XAMPP).

---

## 🔑 Default Clinical Accounts

The seeder initializes demo accounts for immediate clinical evaluation:

| Clinical Role | Username / Email | Password |
| :--- | :--- | :--- |
| **Super Admin / Medical Director** | `admin@aura-ivf.com` | `admin123` |
| **Chief Embryologist** | `embryo@aura-ivf.com` | `admin123` |
| **Consultant Obstetrician** | `obgyn@aura-ivf.com` | `admin123` |
| **Senior Clinical Nurse** | `nurse@aura-ivf.com` | `admin123` |

*(1-click role login shortcuts are also provided directly on the login screen).*

---

## 📂 Project Structure

```
fertility-clinic-mgt-software/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php      # Sign-in, session guards & logout
│   │   └── IvfController.php       # Core clinical operations & file uploads
│   └── Models/                     # Eloquent entities & RBAC permission logic
│       ├── AntenatalRecord.php
│       ├── BabyRecord.php
│       ├── ClinicSetting.php       # Facility baselines & ART registry singleton
│       ├── Couple.php              # Couple record with signed consent file relations
│       ├── CryobankStorage.php
│       ├── CycleOutcome.php
│       ├── DeliveryRecord.php
│       ├── EmbryoTransfer.php
│       ├── FollicularScan.php
│       ├── InfertilityWorkup.php
│       ├── IvfCycle.php
│       ├── Lead.php
│       ├── OpuAndLabRecord.php
│       ├── Patient.php             # Individual female/male patient files
│       ├── User.php                # Staff accounts & canAccessTab() RBAC
│       └── VaccinationSchedule.php
├── database/
│   ├── migrations/                 # Complete relational schema
│   └── seeders/                    # Realistic clinical patient trajectories
├── resources/
│   └── views/ivf/
│       ├── layout.blade.php        # Ergonomic monochrome sidebar & top bar
│       ├── login.blade.php         # Secure login portal with 1-click clinical demos
│       └── index.blade.php         # Modular workspaces & consent document viewer
└── routes/
    └── web.php                     # Protected clinical routes & authorization endpoints
```

---

## 📄 License

This software is open-source and released under the [MIT License](LICENSE). You are free to use, modify, and deploy it in clinical, academic, or commercial healthcare settings.

---

Developed for reproductive medicine and embryology professionals worldwide by [WebOtApp Academy](https://github.com/webotapp-academy).
