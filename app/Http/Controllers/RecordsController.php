<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class RecordsController extends Controller
{
    // 1. Show the Reception Dashboard
    public function index()
    {
        if (Auth::user()->usertype == 'receptionist') {
            // Get latest 10 patients
            $patients = User::where('usertype', 'patient')->latest()->take(10)->get();
            return view('receptionist.home', compact('patients'));
        }
        return redirect()->back();
    }

    // 2. Register a New Patient (Walk-in)
    public function register_patient(Request $request)
    {
        // 1. Create User (Patient)
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->usertype = 'patient';
        $user->password = Hash::make($request->phone);
        // ... inside register_patient function ...

        $user->save(); // User is saved successfully

        // 2. Create the "Visit"
        $appoint = new Appointment;

        // 👇 ADD THIS LINE
        $appoint->patient_id = $user->id; // Fixes the error!

        $appoint->user_id = $user->id;
        $appoint->name = $user->name;
        $appoint->email = $user->email;
        $appoint->phone = $user->phone;
        $appoint->date = date('Y-m-d');

        // 👇 ADD THIS LINE
        $appoint->time = date('H:i'); // Sets time to "Now" (e.g. 14:30)

        $appoint->message = "Walk-in Patient";
        $appoint->status = 'Waiting Triage';
        $appoint->department = 'General';

        $appoint->save();

        return redirect()->back()->with('message', 'Patient Registered & Sent to Triage Queue!');
    }
}
