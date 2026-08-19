<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Patient Details Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $patient->first_name }} {{ $patient->last_name }}</h1>
                    <span class="px-3 py-1 text-xs font-mono font-semibold rounded-full bg-blue-100 text-blue-800">{{ $patient->patient_number ?? 'PAT-#' . $patient->id }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    Gender: <span class="font-medium text-gray-800">{{ ucfirst($patient->gender ?? 'N/A') }}</span> | 
                    Age/DOB: <span class="font-medium text-gray-800">{{ $patient->dob ?? $patient->age ?? 'N/A' }}</span> | 
                    Phone: <span class="font-medium text-gray-800">{{ $patient->phone ?? 'N/A' }}</span>
                </p>
            </div>
            <div>
                <a href="{{ route('patients.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">← Back to Patient List</a>
            </div>
        </div>

        <!-- Encounters Timeline -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Encounter History ({{ $patient->encounters->count() }} Visits)</h2>

            @forelse($patient->encounters as $encounter)
                @php
                    $vitals = $patientVitals ? $patientVitals->where('created_at', '<=', $encounter->created_at)->first() : null;
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-gray-900">Visit: {{ $encounter->created_at->format('d M Y, H:i') }}</span>
                            <span class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-gray-200 text-gray-800">{{ ucfirst($encounter->status) }}</span>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Triage / Vitals -->
                        <div class="space-y-1">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-2">1. Vitals & Triage</h3>
                            @if($vitals)
                                <p class="text-sm text-gray-700">BP: <strong>{{ $vitals->blood_pressure ?? $vitals->bp ?? 'N/A' }}</strong></p>
                                <p class="text-sm text-gray-700">Temp: <strong>{{ $vitals->temperature ?? $vitals->temp ?? 'N/A' }} °C</strong></p>
                                <p class="text-sm text-gray-700">Pulse: <strong>{{ $vitals->pulse_rate ?? $vitals->pulse ?? 'N/A' }} bpm</strong></p>
                                <p class="text-sm text-gray-700">Weight: <strong>{{ $vitals->weight ?? 'N/A' }} kg</strong></p>
                            @else
                                <p class="text-xs text-gray-400 italic">No triage vitals recorded for this visit.</p>
                            @endif
                        </div>

                        <!-- Doctor Consultation -->
                        <div class="space-y-1">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-2">2. Clinical Notes & Diagnosis</h3>
                            @if($encounter->consultation)
                                <p class="text-sm text-gray-700"><strong>Complaints:</strong> {{ $encounter->consultation->chief_complaints ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-700"><strong>Findings:</strong> {{ $encounter->consultation->clinical_notes ?? 'N/A' }}</p>
                                <p class="text-sm text-red-600 font-semibold"><strong>Diagnosis:</strong> {{ $encounter->consultation->diagnosis ?? 'N/A' }}</p>
                            @else
                                <p class="text-xs text-gray-400 italic">No consultation notes recorded.</p>
                            @endif
                        </div>

                        <!-- Pharmacy & Labs -->
                        <div class="space-y-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-2">3. Labs & Medications</h3>
                            <div>
                                <span class="text-xs font-semibold text-gray-500">Lab Orders:</span>
                                @forelse($encounter->labOrders as $lab)
                                    <div class="text-xs bg-purple-50 text-purple-900 p-1 rounded mt-1">
                                        • {{ $lab->testType->name ?? $lab->test_name ?? 'Laboratory Investigation' }} ({{ ucfirst($lab->status) }})
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400">None</p>
                                @endforelse
                            </div>

                            <div>
                                <span class="text-xs font-semibold text-gray-500">Medications:</span>
                                @forelse($encounter->prescriptions as $rx)
                                    @php
                                        $drugName = $rx->drug->item_name ?? $rx->inventory->item_name ?? $rx->drug_name ?? $rx->item_name ?? 'Prescribed Drug';
                                    @endphp
                                    <div class="text-xs bg-green-50 text-green-900 p-1 rounded mt-1">
                                        • <strong>{{ $drugName }}</strong> — {{ $rx->dosage ?? 'Standard Dose' }} (Qty: {{ $rx->quantity ?? 1 }})
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400">None</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 text-center rounded-xl border border-gray-200 text-gray-500">
                    No encounters recorded yet.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
