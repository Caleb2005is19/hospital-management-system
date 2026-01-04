<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecordsController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/upload_appointment', [AppointmentController::class, 'store']);
    Route::get('/approve_appoint/{id}', [AppointmentController::class, 'approved']);
    Route::get('/cancel_appoint/{id}', [AppointmentController::class, 'canceled']);
    Route::get('/treat_patient/{id}', [AppointmentController::class, 'treat_patient']);
    Route::post('/update_prescription/{id}', [AppointmentController::class, 'update_prescription']);
    Route::get('/print_pdf/{id}', [HomeController::class, 'print_pdf']);
    Route::get('/add_vitals/{id}', [NurseController::class, 'add_vitals']);
    Route::post('/store_vitals/{id}', [NurseController::class, 'store_vitals']);
    Route::get('/show_care_page/{id}', [NurseController::class, 'show_care_page']);
    Route::post('/mark_medication_given/{id}', [NurseController::class, 'mark_medication_given']);
    Route::post('/store_nursing_note/{id}', [NurseController::class, 'store_nursing_note']);
    Route::post('/assign_bed/{id}', [NurseController::class, 'assign_bed']);
    Route::get('/shift_reports', [NurseController::class, 'shift_reports']);
    Route::post('/store_shift_report', [NurseController::class, 'store_shift_report']);
    Route::post('/upload_drug', [PharmacyController::class, 'upload_drug']);
    Route::get('/dispense_view/{id}', [PharmacyController::class, 'dispense_view']);
    Route::post('/store_dispense/{id}', [PharmacyController::class, 'store_dispense']);
    Route::get('/create_bill/{id}', [BillingController::class, 'create_bill']);
    Route::post('/store_bill/{id}', [BillingController::class, 'store_bill']);
    Route::get('/print_receipt/{id}', [BillingController::class, 'print_receipt']);
    Route::get('/add_doctor_view', [HomeController::class, 'add_doctor_view']);
    Route::post('/upload_doctor', [HomeController::class, 'upload_doctor']);
    Route::get('/medication_chart/{id}', [NurseController::class, 'show_medication_page']);
    Route::post('/administer_drug/{id}', [NurseController::class, 'administer_drug']);
    Route::get('/add_doctor_view', [AdminController::class, 'add_doctor_view']);
    Route::post('/upload_doctor', [AdminController::class, 'upload_doctor']);
    Route::post('/register_patient_record', [RecordsController::class, 'register_patient']);
    Route::get('/triage_queue', [NurseController::class, 'triage_queue']);
    Route::post('/submit_triage/{id}', [NurseController::class, 'submit_triage']);
    Route::get('/bed_management', [NurseController::class, 'bed_management']);
    Route::post('/store_bed', [NurseController::class, 'store_bed']);
    Route::get('/discharge_bed/{id}', [NurseController::class, 'discharge_bed']);
    Route::get('/bed_assign_view/{id}', [NurseController::class, 'bed_assign_view']);
    Route::post('/assign_bed_store/{id}', [NurseController::class, 'assign_bed_store']);
    Route::get('/show_employees', [AdminController::class, 'show_employees']);
    Route::get('/delete_employee/{id}', [AdminController::class, 'delete_employee']);
    Route::get('/edit_employee_view/{id}', [AdminController::class, 'edit_employee_view']);
    Route::post('/edit_employee/{id}', [AdminController::class, 'edit_employee']);
});

require __DIR__ . '/auth.php';
