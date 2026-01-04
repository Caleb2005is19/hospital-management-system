<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // 1. Save the Appointment to Database
    public function store(Request $request)
    {
        // Validation (Laravel makes this super easy)
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'reason' => 'required|string|max:255',
        ]);

        $data = new Appointment;

        $data->date = $request->date;
        $data->time = $request->time;
        $data->reason = $request->reason;
        $data->status = 'pending';

        // Link to the logged-in user
        // Note: In our migration, we linked 'patient_id' to the 'users' table
        $data->patient_id = Auth::user()->id;

        $data->save();

        return redirect()->back()->with('message', 'Appointment Request Sent Successfully!');
    }
    public function approved($id)
    {
        $data = Appointment::find($id);
        $data->status = 'approved';
        $data->save();
        return redirect()->back();
    }

    public function canceled($id)
    {
        $data = Appointment::find($id);
        $data->status = 'canceled';
        $data->save();
        return redirect()->back();
    }
    // 1. Show the Treatment Page
    public function treat_patient($id)
    {
        $appointment = Appointment::find($id);
        return view('doctor.treat', compact('appointment'));
    }

    // 2. Save the Prescription
    public function update_prescription(Request $request, $id)
    {
        $data = Appointment::find($id);
        $data->reason = $request->reason;
        $data->status = 'approved';
        $data->target_ward = $request->target_ward;
        $data->save();

        // Loop through the inputs
        if ($request->inputs) {
            foreach ($request->inputs as $key => $value) {

                // 👇 THIS IF STATEMENT IS THE FIX 👇
                // We only save if the nurse actually typed a drug name
                if ($value['drug_name'] != null) {

                    $med = new \App\Models\PrescriptionItem;
                    $med->appointment_id = $id;
                    $med->drug_name = $value['drug_name'];
                    $med->dosage = $value['dosage'];
                    $med->frequency = $value['frequency'];
                    $med->duration = $value['duration'];
                    // Only add quantity if your database table has that column
                    $med->quantity = $value['quantity'] ?? '1';
                    $med->save();
                }
            }
        }

        return redirect('/home')->with('message', 'Treatment & Medication Chart Saved!');
    }
}
