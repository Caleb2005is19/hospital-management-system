<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Appointment; // 👈 CHANGE 1: Import the Model

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::id()) {
            $usertype = Auth::user()->usertype;
            if ($usertype == 'admin') {
                // OLD: return view('admin.home');

                // NEW: Use the controller that calculates the math
                return app(\App\Http\Controllers\AdminController::class)->index();
            }
            if (Auth::user()->usertype == 'doctor') {
                // Change variable name from $appoint to $appointments (Plural)
                $appointments = Appointment::whereIn('status', ['approved', 'In Progress'])->get();

                // Update compact to match
                return view('doctor.home', compact('appointments'));
            } else if ($usertype == 'nurse') {
                $nurseWard = Auth::user()->department; // e.g., 'General Ward'

                if ($nurseWard) {
                    $appointments = Appointment::where('status', 'approved')
                        ->where(function ($query) use ($nurseWard) {
                            // 1. Patient is ALREADY in a bed in this ward
                            $query->whereHas('patient.bed', function ($q) use ($nurseWard) {
                                $q->where('ward_name', $nurseWard);
                            })
                                // 2. OR Doctor requested this ward (Admission Queue)
                                ->orWhere('target_ward', $nurseWard);
                        })
                        ->get();
                } else {
                    // Fallback for floating nurses
                    $appointments = Appointment::where('status', 'approved')->get();
                }

                return view('nurse.home', compact('appointments'));
            } else if ($usertype == 'pharmacist') {
                // Redirect to the dedicated Pharmacy Controller
                return app(PharmacyController::class)->index();
            } else if ($usertype == 'cashier') {
                return app(BillingController::class)->index();
            } else if ($usertype == 'receptionist') {
                return app(RecordsController::class)->index();
            } else if ($usertype == 'receptionist') {
                return app(RecordsController::class)->index();
            } else {
                // 👇 Fetch logged-in user's appointments
                $appointments = Appointment::where('patient_id', Auth::id())->get();

                return view('dashboard', compact('appointments'));
            }
        }
    }
    // 1. Show the Add Employee Form
    public function add_doctor_view()
    {
        if (Auth::user()->usertype == 'admin') {
            return view('admin.add_doctor');
        } else {
            return redirect()->back();
        }
    }

    // 2. Save the Employee
    public function upload_doctor(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->usertype = $request->usertype; // doctor, nurse, pharmacist, receptionist
        $user->department = $request->department; // Optional (for nurses/doctors)
        $user->password = bcrypt($request->password); // Default password?

        $user->save();

        return redirect()->back()->with('message', 'New Employee Added Successfully!');
    }
    public function print_pdf($id)
    {
        $appointment = Appointment::find($id);
        return view('pdf_view', compact('appointment'));
    }
}
