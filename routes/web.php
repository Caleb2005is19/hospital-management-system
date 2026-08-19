<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TriageController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\RevenueControlController;
use App\Http\Controllers\AdminController;


Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Core Home & Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1. Reception & Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::post('/patients/{id}/encounter', [PatientController::class, 'startEncounter'])->name('patients.encounter');

    // 2. Triage Module
    Route::get('/triage', [TriageController::class, 'index'])->name('triage.index');
    Route::get('/triage/{encounter}', [TriageController::class, 'create'])->name('triage.create');
    Route::post('/triage/{encounter}', [TriageController::class, 'store'])->name('triage.store');

    // 3. Doctor Portal
    Route::get('/doctor/queue', [DoctorController::class, 'index'])->name('doctor.queue');
    Route::get('/doctor/consult/{encounter}', [DoctorController::class, 'consult'])->name('doctor.consult');
    Route::post('/doctor/consult/{encounter}', [DoctorController::class, 'store'])->name('doctor.store');

    // 4. Laboratory Diagnostics
    Route::get('/lab/queue', [LabController::class, 'index'])->name('lab.index');
    Route::post('/lab/order/{encounter}', [LabController::class, 'storeOrder'])->name('lab.order');
    Route::post('/lab/result/{order}', [LabController::class, 'updateResult'])->name('lab.result');
    Route::get('/lab/print/{encounter}', [LabController::class, 'printReport'])->name('lab.print');

    // 5. Pharmacy Dispensary, POS & Inventory
    Route::get('/pharmacy', [PharmacyController::class, 'index'])->name('pharmacy.index');
    Route::post('/pharmacy/prescription/{encounter}', [PharmacyController::class, 'storePrescription'])->name('pharmacy.prescription.store');
    Route::post('/pharmacy/dispense/{prescription}', [PharmacyController::class, 'dispense'])->name('pharmacy.dispense');
    Route::post('/pharmacy/otc-sale', [PharmacyController::class, 'otcSale'])->name('pharmacy.otc');
    Route::post('/pharmacy/drugs', [PharmacyController::class, 'storeDrug'])->name('pharmacy.drug.store');
    Route::post('/pharmacy/adjust/{inventory}', [PharmacyController::class, 'adjustStock'])->name('pharmacy.adjust');
    Route::get('/pharmacy/print/{encounter}', [PharmacyController::class, 'printLabel'])->name('pharmacy.print');

    // 6. Billing & Cashier Desk
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/encounter/{encounter}', [BillingController::class, 'show'])->name('billing.show');
    Route::post('/billing/payment/{invoice}', [BillingController::class, 'processPayment'])->name('billing.payment');
  Route::get('/billing/print/{encounter}', [BillingController::class, 'printReceipt'])->name('billing.print');
      // Billing & Cashier Session Routes
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/session/start', [BillingController::class, 'startSession'])->name('billing.session.start');
    Route::post('/billing/session/end/{session}', [BillingController::class, 'endSession'])->name('billing.session.end');
    Route::get('/billing/encounter/{encounter}', [BillingController::class, 'show'])->name('billing.show');
    Route::post('/billing/payment/{invoice}', [BillingController::class, 'processPayment'])->name('billing.payment');
    Route::get('/billing/receipt/{receipt}', [BillingController::class, 'printReceipt'])->name('billing.receipt.print');

      // 7. Revenue Protection & Anti-Fraud Control Center
    Route::get('/revenue-control', [RevenueControlController::class, 'index'])->name('revenue.index');
    Route::post('/revenue-control/adjustment/request', [RevenueControlController::class, 'requestAdjustment'])->name('revenue.adjustment.request');
    Route::post('/revenue-control/adjustment/action/{adjustment}', [RevenueControlController::class, 'actionAdjustment'])->name('revenue.adjustment.action');
    Route::post('/revenue-control/charge/reverse/{charge}', [RevenueControlController::class, 'reverseCharge'])->name('revenue.charge.reverse');
      // Staff & Employee Management
    Route::get('/employees', [AdminController::class, 'show_employees'])->name('employees.index');
    Route::get('/employees/create', [AdminController::class, 'add_doctor_view'])->name('employees.create');
    Route::post('/employees/store', [AdminController::class, 'upload_doctor'])->name('employees.store');
    Route::get('/employees/edit/{id}', [AdminController::class, 'edit_employee_view'])->name('employees.edit');
    Route::post('/employees/update/{id}', [AdminController::class, 'edit_employee'])->name('employees.update');
    Route::delete('/employees/delete/{id}', [AdminController::class, 'delete_employee'])->name('employees.destroy');
Route::get('/employees/create', [HomeController::class, 'add_doctor_view'])->name('employees.create');
Route::post('/employees/store', [HomeController::class, 'upload_doctor'])->name('employees.store');
Route::get('/print_pdf/{id}', [HomeController::class, 'print_pdf'])->name('print_pdf');





});

require __DIR__ . '/auth.php';

Route::put('/lab/order/{id}', [\App\Http\Controllers\LabController::class, 'update'])->name('lab.update')->middleware(['auth']);
