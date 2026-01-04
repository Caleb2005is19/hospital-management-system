<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Appointment;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth; // <--- This was the missing fix

class AdminController extends Controller
{
    // 1. Show the Form to Add a User
    public function add_doctor_view()
    {
        return view('admin.add_doctor');
    }

    // 2. Save the New Employee to Database
    public function upload_doctor(Request $request)
    {
        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->room = $request->room;
        $user->department = $request->department;

        $user->usertype = $request->usertype;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->back()->with('message', 'Employee Added Successfully!');
    }

    // 3. Super Admin Dashboard Logic
    public function index()
    {
        if (Auth::id()) {
            if (Auth::user()->usertype == 'admin') {

                // 1. Key Metrics (Counters)
                $total_doctors = User::where('usertype', 'doctor')->count();
                $total_patients = User::where('usertype', 'patient')->count();
                $total_appointments = Appointment::count();

                // 2. Financials (Total Earnings)
                $total_revenue = Bill::sum('total_amount');

                // 3. Chart Data (Appointment Status)
                $status_counts = [
                    'approved' => Appointment::where('status', 'approved')->count(),
                    'canceled' => Appointment::where('status', 'canceled')->count(),
                    'completed' => Appointment::where('status', 'completed')->count(),
                ];

                return view('admin.home', compact('total_doctors', 'total_patients', 'total_appointments', 'total_revenue', 'status_counts'));
            } else {
                return redirect()->back();
            }
        } else {
            return redirect('login');
        }
    }
    // 4. Show All Employees (Doctors, Nurses, etc.)
    public function show_employees()
    {
        // Get everyone who is NOT a patient
        $employees = User::where('usertype', '!=', 'patient')->get();
        return view('admin.show_employees', compact('employees'));
    }

    // 5. Delete an Employee
    public function delete_employee($id)
    {
        $user = User::find($id);

        // Prevent Admin from deleting themselves!
        if ($user->id == Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->back()->with('message', 'Employee account deleted successfully.');
    }

    // 6. Show Edit Form
    public function edit_employee_view($id)
    {
        $user = User::find($id);
        return view('admin.update_employee', compact('user'));
    }

    // 7. Save Edited Details
    public function edit_employee(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->usertype = $request->usertype;
        $user->department = $request->department;
        $user->room = $request->room;

        $user->save();

        return redirect('/show_employees')->with('message', 'Employee details updated!');
    }
}
