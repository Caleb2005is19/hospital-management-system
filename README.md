🏥 Hospital Management System (HMS)

> **A comprehensive, role-based digital health solution for modern hospitals.**
> *Streamlining patient care from Reception to Recovery.*

---

## 🚀 Overview

This Full-Stack Hospital Management System is designed to digitize administrative and clinical workflows. It features a secure, multi-user architecture that connects **Patients, Doctors, Nurses, Pharmacists, Receptionists, and Administrators** into a single, cohesive platform.

Whether managing bed occupancy in real-time or tracking revenue analytics, this system provides the tools necessary for efficient hospital administration.

## ✨ Key Features

### 👨‍⚕️ Clinical Modules

* **Doctor Dashboard:** View appointments, access patient history, and prescribe medication digitally.
* **Nurse Workstation:** Triage patients (Vitals), manage Ward Beds (Admissions/Discharge), and administer care.
* **Digital Prescription:** Automated medication charts that flow directly to the Pharmacy.

### 🏥 Administrative Modules

* **Reception & Records:** Walk-in patient registration and file management.
* **Pharmacy & Inventory:** Manage drug stock and dispense prescriptions.
* **Bed Management:** Visual mapping of Wards (General, ICU, Maternity) with real-time "Occupied/Available" status.
* **Billing & Finance:** Automated invoicing for consultations and medicines.

### 📊 Analytics & Control

* **CEO Dashboard:** Real-time financial reports, patient counters, and appointment status graphs.
* **Staff Management:** Full CRUD (Create, Read, Update, Delete) capabilities for hospital employees.

---

## 🛠️ Technology Stack

* **Framework:** Laravel 10+ (PHP)
* **Database:** MySQL
* **Frontend:** Blade Templates & Tailwind CSS
* **Charts:** Chart.js
* **Authentication:** Laravel Jetstream / Fortify

---

## ⚙️ Installation Guide

Follow these steps to run the project locally on your machine.

### 1. Clone the Repository

```bash
git clone https://github.com/Caleb2005is19/hospital-management-system.git
cd hospital-management-system

```

### 2. Install Dependencies

```bash
composer install
npm install && npm run build

```

### 3. Environment Setup

Rename the example environment file and generate your application key:

```bash
cp .env.example .env
php artisan key:generate

```

### 4. Database Configuration

1. Create a MySQL database named `hospital`.
2. Open your `.env` file and update the database settings:
```env
DB_DATABASE=hospital
DB_USERNAME=root
DB_PASSWORD=

```



### 5. Run Migrations

Set up the database tables:

```bash
php artisan migrate

```

### 6. Start the Server

```bash
php artisan serve

```

Visit `http://127.0.0.1:8000` in your browser.

---

## 👤 User Roles & Login Credentials (Demo)

*Note: You will need to register these users manually or seed the database.*

| Role | Features Access |
| --- | --- |
| **Admin** | Dashboard, Revenue, Employee Management |
| **Doctor** | Appointments, Prescriptions, Patient History |
| **Nurse** | Triage, Bed Assignment, Vitals |
| **Receptionist** | Patient Registration, Queue Management |
| **Pharmacist** | Medicine Dispensing, Stock View |
| **Cashier** | Payment Processing, Invoice Generation |

---
PROPRIETARY LICENSE AGREEMENT

Copyright (c) 2026 Caleb. All Rights Reserved.

NOTICE:  All information contained herein is, and remains the property of [Your Name]. 
The intellectual and technical concepts contained herein are proprietary to [Your Name] 
and may be covered by patents or patents in process, and are protected by trade secret or copyright law.

Dissemination of this information, reproduction of this material, or use of this software 
for any purpose (commercial or non-commercial) is strictly forbidden unless prior written 
permission is obtained from Caleb Nyabuto.

For licensing inquiries or permission requests, please contact: cmomanyi06@gmail.com

> **Developed by Caleb Nyabuto
> *Passionate about building scalable web solutions.*
