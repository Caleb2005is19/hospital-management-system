<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Primary Portal Router based on authenticated user role
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $usertype = $user->usertype;

        // 1. Hospital Administrator Command Center
        if ($usertype === 'admin') {
            $totalRevenue = Payment::where('status', 'completed')->sum('amount');
            $activeClinicians = User::whereIn('usertype', ['doctor', 'nurse', 'lab_tech', 'pharmacist'])->count();
            $registeredPatients = Patient::count();
            $totalVisits = Encounter::count();

            $encounters = Encounter::with(['patient'])
                ->latest('updated_at')
                ->take(15)
                ->get();

            return view('admin.home', compact(
                'totalRevenue',
                'activeClinicians',
                'registeredPatients',
                'totalVisits',
                'encounters'
            ));
        }

        // 2. Doctor / Clinician Queue
        if ($usertype === 'doctor') {
            $appointments = Appointment::whereIn('status', ['approved', 'In Progress', 'waiting_doctor'])->latest()->get();
            return view('doctor.home', compact('appointments'));
        }

        // 3. Nurse & Triage Station
        if ($usertype === 'nurse') {
            $nurseWard = $user->department;

            if ($nurseWard) {
                $appointments = Appointment::where('status', 'approved')
                    ->where(function ($query) use ($nurseWard) {
                        $query->whereHas('patient.bed', function ($q) use ($nurseWard) {
                            $q->where('ward_name', $nurseWard);
                        })->orWhere('target_ward', $nurseWard);
                    })
                    ->latest()
                    ->get();
            } else {
                $appointments = Appointment::where('status', 'approved')->latest()->get();
            }

            return view('nurse.home', compact('appointments'));
        }

        // 4. Pharmacy & Dispensary Station
        if ($usertype === 'pharmacist') {
            return app(PharmacyController::class)->index();
        }

        // 5. Billing & Revenue Desk
        if ($usertype === 'cashier') {
            return app(BillingController::class)->index();
        }

        // 6. Reception & Registration Desk
        if ($usertype === 'receptionist') {
            return redirect()->route('patients.index');
        }

        // 7. Patient Client Portal (Default / Regular Users)
        $appointments = Appointment::where('patient_id', $user->id)
            ->orWhere('email', $user->email)
            ->latest()
            ->get();

        return view('dashboard', compact('appointments'));
    }

    /**
     * Show form to add new hospital staff / employee (Admin Only)
     */
    public function add_doctor_view()
    {
        if (!Auth::check() || Auth::user()->usertype !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access. Administrator privileges required.');
        }

        return view('admin.add_doctor');
    }

    /**
     * Save new hospital staff member
     */
    public function upload_doctor(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'usertype' => 'required|string|in:admin,doctor,nurse,pharmacist,receptionist,lab_tech,cashier',
            'department' => 'nullable|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->usertype = $validated['usertype'];
        $user->department = $validated['department'] ?? null;
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('dashboard')->with('message', "New staff member ({$user->name}) added and activated successfully!");
    }

    /**
     * Handle Patient Outpatient Appointment Booking Request
     */
    public function upload_appointment(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'reason' => 'required|string|max:500',
        ]);

        $appointment = new Appointment();
        $appointment->patient_id = Auth::id();
        $appointment->name = Auth::user()->name;
        $appointment->email = Auth::user()->email;
        $appointment->phone = Auth::user()->phone ?? 'N/A';
        $appointment->date = $validated['date'];
        $appointment->time = $validated['time'];
        $appointment->reason = $validated['reason'];
        $appointment->status = 'In Progress';
        $appointment->save();

        return redirect()->back()->with('message', 'Your appointment request has been submitted to the clinic.');
    }

    /**
     * Generate & Download PDF Clinical Summary / Prescription Slip
     */
    public function print_pdf($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('pdf_view', compact('appointment'));
    }
}
