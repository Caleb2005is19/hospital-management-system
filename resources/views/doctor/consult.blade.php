<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <span class="p-2 bg-teal-100 text-teal-700 rounded-xl text-lg">🩺</span>
                    Clinical Encounter — {{ $encounter->patient->name }}
                </h2>
                <p class="text-xs text-slate-500 font-mono mt-0.5">Encounter ID: {{ $encounter->encounter_number }} • Visit Type: {{ $encounter->type }}</p>
            </div>
            <a href="{{ route('doctor.queue') }}" class="text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl transition">
                ← Back to Queue
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if(session('message'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm text-xs font-semibold text-emerald-800">
                ✓ {{ session('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm text-xs text-red-700">
                <ul class="list-disc pl-5 space-y-0.5 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Patient & Triage Summary Banner -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block tracking-wider">Patient Demographics</span>
                <strong class="text-base text-slate-900 block font-black">{{ $encounter->patient->name }}</strong>
                <span class="text-xs text-slate-500 font-mono">
                    {{ $encounter->patient->patient_number }} • {{ $encounter->patient->gender }} • 
                    {{ $encounter->patient->dob ? \Carbon\Carbon::parse($encounter->patient->dob)->age . ' yrs' : 'Age N/A' }}
                </span>
            </div>

            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block tracking-wider">Allergies & Contraindications</span>
                <strong class="text-sm {{ $encounter->patient->allergies ? 'text-red-600 font-bold' : 'text-slate-500' }} block mt-0.5">
                    {{ $encounter->patient->allergies ?? 'No Known Allergies' }}
                </strong>
            </div>

            <div class="md:col-span-2 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-indigo-900 font-bold text-[11px] uppercase tracking-wider">Triage Vitals Baseline</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase
                        {{ $encounter->triage?->priority === 'Emergency' ? 'bg-red-100 text-red-800' : 
                          ($encounter->triage?->priority === 'Urgent' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                        Priority: {{ $encounter->triage?->priority ?? 'Standard' }}
                    </span>
                </div>
                
                <div class="grid grid-cols-4 gap-2 text-xs font-mono">
                    <div><span class="text-slate-400 text-[10px] block">BP</span><strong>{{ $encounter->triage?->bp ?? 'N/A' }}</strong></div>
                    <div><span class="text-slate-400 text-[10px] block">TEMP</span><strong>{{ $encounter->triage?->temp ? $encounter->triage->temp . '°C' : 'N/A' }}</strong></div>
                    <div><span class="text-slate-400 text-[10px] block">PULSE</span><strong>{{ $encounter->triage?->pulse ?? 'N/A' }} bpm</strong></div>
                    <div><span class="text-slate-400 text-[10px] block">SPO2</span><strong>{{ $encounter->triage?->spo2 ? $encounter->triage->spo2 . '%' : 'N/A' }}</strong></div>
                </div>

                @if($encounter->triage?->chief_complaint)
                    <div class="text-xs text-slate-700 mt-2.5 pt-2 border-t border-slate-200">
                        <strong class="text-slate-900 font-semibold">Chief Complaint:</strong> "{{ $encounter->triage->chief_complaint }}"
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT COLUMN: Consultation & Diagnosis Form (2 Cols) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span>✍️</span> Clinical Examination & Decision Notes
                </h3>

                <!-- FORM 1: CONSULTATION NOTE & ENCOUNTER ROUTING -->
                <form method="POST" action="{{ route('doctor.store', $encounter->id) }}" class="space-y-4 text-sm">
                    @csrf

                    <div>
                        <label class="block font-bold text-slate-700 text-xs uppercase mb-1">History of Presenting Illness (HPI) *</label>
                        <textarea name="history_presenting_illness" rows="3" required placeholder="Patient reports symptom onset, duration, severity, modifying factors..." 
                            class="w-full border-slate-300 rounded-xl p-3 text-xs focus:ring-teal-500 focus:border-teal-500 bg-slate-50/50">{{ old('history_presenting_illness', $encounter->consultation?->history_presenting_illness) }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 text-xs uppercase mb-1">Physical Examination Findings</label>
                        <textarea name="physical_examination" rows="3" placeholder="General exam, chest, CVS, abdomen, CNS findings..." 
                            class="w-full border-slate-300 rounded-xl p-3 text-xs focus:ring-teal-500 focus:border-teal-500 bg-slate-50/50">{{ old('physical_examination', $encounter->consultation?->physical_examination) }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 text-xs uppercase mb-1">Working / Confirmed Diagnosis *</label>
                        <input type="text" name="diagnosis" required placeholder="e.g. Acute Tonsillopharyngitis, Malaria (Confirmed), UTI..." 
                            value="{{ old('diagnosis', $encounter->consultation?->diagnosis) }}"
                            class="w-full border-slate-300 rounded-xl p-3 text-xs font-bold text-slate-900 focus:ring-teal-500 focus:border-teal-500 bg-slate-50/50">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 text-xs uppercase mb-1">Management Plan & Clinical Instructions</label>
                        <textarea name="treatment_plan" rows="2" placeholder="Supportive care, hydration, lifestyle, follow-up timeline..." 
                            class="w-full border-slate-300 rounded-xl p-3 text-xs focus:ring-teal-500 focus:border-teal-500 bg-slate-50/50">{{ old('treatment_plan', $encounter->consultation?->treatment_plan) }}</textarea>
                    </div>

                    <!-- Workflow Router -->
                    <div class="bg-teal-50/50 p-4 rounded-xl border border-teal-100 mt-6">
                        <label class="block font-bold text-teal-950 text-xs uppercase mb-2">Disposition / Route Patient Next *</label>
                        <select name="next_action" required class="w-full border-slate-300 rounded-xl p-2.5 text-xs font-bold text-slate-800 bg-white focus:ring-teal-500">
                            <option value="waiting_pharmacy">Send to Pharmacy (Dispense Prescriptions)</option>
                            <option value="waiting_lab">Send to Laboratory (Order Lab Tests)</option>
                            <option value="admitted">Admit Patient to Ward</option>
                            <option value="discharged">Discharge & Issue Bill</option>
                        </select>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                        <a href="{{ route('doctor.queue') }}" class="text-slate-500 font-bold text-xs hover:text-slate-700">← Exit to Queue</a>
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition shadow-sm hover:shadow">
                            Save Consultation & Route Patient →
                        </button>
                    </div>
                </form>
            </div>

            <!-- RIGHT COLUMN: Diagnostics, Prescriptions & Past History (1 Col) -->
            <div class="space-y-6">

                <!-- 1. LAB ORDERS & LIVE FINDINGS CARD -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-purple-100 space-y-3">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-purple-900 flex items-center gap-1.5">
                            <span>🧪</span> Laboratory Investigations
                        </h3>
                        @if($encounter->labOrders && $encounter->labOrders->count() > 0)
                            <a href="{{ route('lab.print', $encounter->id) }}" target="_blank" 
                               class="text-[11px] font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 px-2.5 py-1 rounded-lg border border-purple-200 transition flex items-center gap-1">
                                <span>🖨️</span> Print Lab Slip
                            </a>
                        @endif
                    </div>

                    <!-- Lab Test Requisition Form -->
                    <form method="POST" action="{{ route('lab.order', $encounter->id) }}" class="space-y-3">
                        @csrf
                        <div class="space-y-1.5 max-h-44 overflow-y-auto border border-slate-100 p-2 rounded-xl bg-slate-50/50 text-xs">
                            @forelse($availableTests ?? [] as $test)
                                <label class="flex items-center justify-between p-1.5 hover:bg-white rounded-lg cursor-pointer transition">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="lab_test_ids[]" value="{{ $test->id }}" class="rounded text-purple-600 focus:ring-purple-500">
                                        <span class="font-medium text-slate-800 text-[11px]">{{ $test->name }}</span>
                                    </div>
                                    <span class="text-slate-400 font-mono text-[10px]">KSh {{ number_format($test->price, 0) }}</span>
                                </label>
                            @empty
                                <p class="text-slate-400 italic text-center py-2 text-xs">No lab tests available in catalog.</p>
                            @endforelse
                        </div>

                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-xl text-xs transition shadow-sm">
                            Send Order to Laboratory Queue 🧪
                        </button>
                    </form>

                    <!-- Completed & Pending Test Results Feed -->
                    @if($encounter->labOrders && $encounter->labOrders->count() > 0)
                        <div class="pt-3 border-t border-slate-100 space-y-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Diagnostic Findings:</span>
                            @foreach($encounter->labOrders as $order)
                                <div class="p-2.5 rounded-xl border {{ $order->status === 'completed' ? 'bg-emerald-50/70 border-emerald-200' : 'bg-slate-50 border-slate-200' }} text-xs space-y-1">
                                    <div class="flex justify-between items-center">
                                        <strong class="text-slate-900 text-[11px]">{{ $order->labTest?->name }}</strong>
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase {{ $order->status === 'completed' ? 'bg-emerald-200 text-emerald-900' : 'bg-amber-100 text-amber-800' }}">
                                            {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </div>

                                    @if($order->result)
                                        <div class="font-black text-slate-900 text-xs bg-white p-1.5 rounded border border-emerald-100">
                                            Result: {{ $order->result }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 2. PHARMACY PRESCRIPTION CARD -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100 space-y-3">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-900 flex items-center gap-1.5">
                            <span>💊</span> Prescribe Medication
                        </h3>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                            Auto-Routes
                        </span>
                    </div>

                    <!-- Prescription Form -->
                    <form method="POST" action="{{ route('pharmacy.prescription.store', $encounter->id) }}" class="space-y-2.5 text-xs">
                        @csrf
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Select Medicine *</label>
                            <select name="inventory_id" required class="w-full border-slate-300 rounded-xl p-2 text-xs font-semibold bg-slate-50/50 focus:ring-emerald-500">
                                <option value="">-- Choose from Available Stock --</option>
                                @forelse($availableDrugs ?? [] as $drug)
                                    <option value="{{ $drug->id }}">
                                        {{ $drug->item_name }} (Stock: {{ $drug->stock_quantity }} | KSh {{ number_format($drug->unit_price, 2) }})
                                    </option>
                                @empty
                                    <option disabled>No medications in stock.</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Dosage *</label>
                                <input type="text" name="dosage" placeholder="e.g. 500mg" required class="w-full border-slate-300 rounded-xl p-2 text-xs bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Frequency *</label>
                                <input type="text" name="frequency" placeholder="e.g. TDS (8 hrly)" required class="w-full border-slate-300 rounded-xl p-2 text-xs bg-slate-50/50">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Duration *</label>
                                <input type="text" name="duration" placeholder="e.g. 5 days" required class="w-full border-slate-300 rounded-xl p-2 text-xs bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Total Qty *</label>
                                <input type="number" name="quantity_prescribed" min="1" placeholder="e.g. 15" required class="w-full border-slate-300 rounded-xl p-2 text-xs font-bold bg-slate-50/50">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl text-xs transition shadow-sm">
                            Add & Route to Pharmacy 💊
                        </button>
                    </form>

                    <!-- Prescribed Medications List -->
                    @if($encounter->prescriptions && $encounter->prescriptions->count() > 0)
                        <div class="pt-3 border-t border-slate-100 space-y-1.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Prescriptions:</span>
                            @foreach($encounter->prescriptions as $presc)
                                <div class="p-2.5 rounded-xl border {{ $presc->status === 'dispensed' ? 'bg-emerald-50/70 border-emerald-200' : 'bg-slate-50 border-slate-200' }} flex justify-between items-center text-xs">
                                    <div>
                                        <strong class="text-slate-900 block text-[11px]">{{ $presc->drug?->item_name }}</strong>
                                        <span class="text-slate-500 text-[10px]">{{ $presc->dosage }} • {{ $presc->frequency }} for {{ $presc->duration }} (Qty: {{ $presc->quantity_prescribed }})</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase {{ $presc->status === 'dispensed' ? 'bg-emerald-200 text-emerald-900' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $presc->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 3. LONGITUDINAL MEDICAL HISTORY -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2">
                        📜 Past Encounters Record
                    </h3>

                    <div class="space-y-2.5 max-h-[300px] overflow-y-auto text-xs">
                        @forelse($pastEncounters as $past)
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-teal-700 font-mono text-[11px]">{{ $past->encounter_number }} ({{ $past->type }})</span>
                                    <span class="text-slate-400 text-[10px]">{{ $past->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="text-slate-900 font-semibold text-[11px]">
                                                        {{ $past->consultation?->provisional_diagnosis ?? 'No diagnosis documented' }}
                                </div>
                                <p class="text-[10px] text-slate-500 line-clamp-2">
                                    {{ $past->consultation?->doctor_notes ?? 'No notes recorded.' }}
                                </p>
                            </div>
                        @empty
                            <div class="p-4 text-center text-slate-400 text-xs italic">
                                No previous medical history recorded.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
