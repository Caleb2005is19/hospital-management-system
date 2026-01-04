<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vital;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\MedicationHistory;
use App\Models\NursingNote;
use App\Models\Bed;
use App\Models\ShiftHandover;
use App\Models\PrescriptionItem; // Add this at top

class NurseController extends Controller
{
    // 1. Show the Form
    public function add_vitals($id)
    {
        $patient = User::find($id);
        return view('nurse.add_vitals', compact('patient'));
    }

    // 2. Store the Data
    public function store_vitals(Request $request, $id)
    {
        $vital = new Vital;
        $vital->patient_id = $id;
        $vital->nurse_id = Auth::id(); // The currently logged in Nurse

        $vital->temperature = $request->temperature;
        $vital->blood_pressure = $request->blood_pressure;
        $vital->heart_rate = $request->heart_rate;
        $vital->nurse_note = $request->nurse_note;

        $vital->save();

        return redirect('/home')->with('message', 'Vitals Recorded Successfully');
    }
    public function assign_bed(Request $request, $patient_id)
    {
        // 1. Find the bed selected by the nurse
        $bed = Bed::find($request->bed_id);

        // 2. Put the patient in it
        $bed->patient_id = $patient_id;
        $bed->save();

        return redirect()->back()->with('message', 'Patient assigned to ' . $bed->ward_name . ' - ' . $bed->bed_number);
    }
    public function show_care_page($appointment_id)
    {
        $appointment = Appointment::find($appointment_id);

        // Fetch Vitals
        $vitals = Vital::where('patient_id', $appointment->patient_id)->latest()->get();

        // 👇 Fetch Nursing Notes (New)
        $notes = NursingNote::where('patient_id', $appointment->patient_id)->latest()->get();

        return view('nurse.care_page', compact('appointment', 'vitals', 'notes'));
    }
    public function store_nursing_note(Request $request, $patient_id)
    {
        $note = new NursingNote;
        $note->patient_id = $patient_id;
        $note->nurse_id = Auth::id();
        $note->note = $request->note;
        $note->type = $request->type;
        $note->save();

        return redirect()->back()->with('message', 'Observation note added.');
    }

    // 2. Mark Medication as Given
    public function mark_medication_given(Request $request, $id)
    {
        $history = new MedicationHistory;
        $history->appointment_id = $id;
        $history->nurse_id = Auth::id();
        $history->status = 'Given';
        $history->remarks = $request->remarks;
        $history->administered_at = now();

        $history->save();

        return redirect()->back()->with('message', 'Medication administration recorded successfully!');
    }
    // 1. Show the Handover Board
    public function shift_reports()
    {
        // Get the last 10 reports (most recent first)
        $reports = ShiftHandover::with('nurse')->latest()->take(10)->get();

        return view('nurse.shift_reports', compact('reports'));
    }

    // 2. Save a New Report
    public function store_shift_report(Request $request)
    {
        $report = new ShiftHandover;
        $report->nurse_id = Auth::id();
        $report->shift_type = $request->shift_type;
        $report->notes = $request->notes;
        $report->save();

        return redirect()->back()->with('message', 'Shift Report Posted Successfully!');
    }
    // 1. Show the Page
    public function show_medication_page($appointment_id)
    {
        $appointment = Appointment::with('patient.bed')->find($appointment_id);

        // Fetch the detailed list of drugs for this visit
        $meds = PrescriptionItem::where('appointment_id', $appointment_id)->get();

        // Fetch history (so we can see what was given)
        $history = MedicationHistory::where('appointment_id', $appointment_id)->with('nurse')->latest()->get();

        return view('nurse.medication_chart', compact('appointment', 'meds', 'history'));
    }

    // 2. Administer Dose
    public function administer_drug($prescription_item_id)
    {
        $item = PrescriptionItem::find($prescription_item_id);

        $log = new MedicationHistory;
        $log->appointment_id = $item->appointment_id;
        $log->nurse_id = Auth::id();

        // We construct a string to save into the old 'status' field, or you can add new columns later
        $log->status = "Given: " . $item->drug_name . " (" . $item->dosage . ")";
        $log->administered_at = now();
        $log->save();

        return redirect()->back()->with('message', 'Dose Recorded: ' . $item->drug_name);
    }
    // 1. Show Triage Queue
    public function triage_queue()
    {
        // Get all patients waiting for triage
        $patients = Appointment::where('status', 'Waiting Triage')->get();
        return view('nurse.triage_queue', compact('patients'));
    }

    // 2. Submit Triage & Route Patient
    public function submit_triage(Request $request, $id)
    {
        $appoint = Appointment::find($id);

        // Save Vitals (We need to ensure these columns exist in appointments table)
        // If you haven't added them to appointments table yet, we store in message or new columns
        // Let's assume we save them as a "Nurse Note" in the message for now to keep it simple
        // OR create new columns (Recommended).

        $vitals = "BP: " . $request->bp . " | Temp: " . $request->temp . " | Weight: " . $request->weight;
        $appoint->message = $vitals . " | Note: " . $request->notes;

        // Route the Patient
        if ($request->destination == 'Doctor') {
            $appoint->status = 'In Progress'; // Ready for Doctor
        } else {
            $appoint->status = 'OPD'; // Sent to OPD Room
        }

        $appoint->save();

        return redirect()->back()->with('message', 'Vitals Recorded. Patient sent to ' . $request->destination);
    }
    // Import at the top

    // 1. Show Bed Management Page
    public function bed_management()
    {
        // Get all beds, ordered by Ward
        $beds = Bed::all();
        return view('nurse.bed_management', compact('beds'));
    }

    // 2. Add a New Bed (Nurse Action)
    public function store_bed(Request $request)
    {
        $bed = new Bed;
        $bed->ward_name = $request->ward_name;
        $bed->bed_number = $request->bed_number;
        $bed->save();

        return redirect()->back()->with('message', 'New Bed Added Successfully!');
    }

    // 3. Discharge Patient (Free up the bed)

    // 1. Show Bed Assignment Page
    public function bed_assign_view($id)
    {
        $appointment = Appointment::find($id);

        // Find empty beds in the specific ward requested by the Doctor
        $available_beds = Bed::where('ward_name', $appointment->target_ward)
            ->where('status', 'Available')
            ->get();

        return view('nurse.assign_bed', compact('appointment', 'available_beds'));
    }

    // 2. Save the Assignment
    public function assign_bed_store(Request $request, $id)
    {
        // A. Update the Bed (Mark as Occupied)
        $bed = Bed::find($request->bed_id);
        $bed->status = 'Occupied';
        $bed->patient_id = $request->patient_id; // Link patient ID
        $bed->save();

        // B. Update the Appointment (Optional: Mark as Admitted)
        $appoint = Appointment::find($id);
        $appoint->status = 'Admitted';
        $appoint->save();

        return redirect('/bed_management')->with('message', 'Patient successfully admitted to ' . $bed->bed_number);
    }
    public function discharge_bed($id)
    {
        $bed = Bed::find($id);

        // 1. Find the patient's active appointment
        if ($bed->patient_id) {
            $appointment = Appointment::where('user_id', $bed->patient_id)
                ->where('status', 'Admitted') // Find the current admission
                ->latest()
                ->first();

            // 2. Mark appointment as "Completed" (Ready for Billing)
            if ($appointment) {
                $appointment->status = 'completed';
                $appointment->save();
            }
        }

        // 3. Free up the Bed
        $bed->status = 'Available';
        $bed->patient_id = null;
        $bed->save();

        return redirect()->back()->with('message', 'Patient Discharged & Sent to Billing!');
    }
}
