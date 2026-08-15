<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🩺 Triage Assessment — {{ $encounter->patient->name }} ({{ $encounter->encounter_number }})
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 space-y-6">

            <!-- Patient Summary Banner -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded text-sm grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <span class="text-gray-500 block text-xs">Patient Number</span>
                    <strong class="text-gray-800">{{ $encounter->patient->patient_number }}</strong>
                </div>
                <div>
                    <span class="text-gray-500 block text-xs">Gender / Age</span>
                    <strong class="text-gray-800">{{ $encounter->patient->gender }} 
                        @if($encounter->patient->dob)
                            ({{ \Carbon\Carbon::parse($encounter->patient->dob)->age }} yrs)
                        @endif
                    </strong>
                </div>
                <div>
                    <span class="text-gray-500 block text-xs">Known Allergies</span>
                    <strong class="text-red-600">{{ $encounter->patient->allergies ?? 'None Reported' }}</strong>
                </div>
                <div>
                    <span class="text-gray-500 block text-xs">Encounter Type</span>
                    <strong class="text-blue-700 font-bold">{{ $encounter->type }}</strong>
                </div>
            </div>

            <form method="POST" action="{{ route('triage.store', $encounter->id) }}" class="space-y-6">
                @csrf

                <!-- Vital Signs Grid -->
                <div>
                    <h4 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wide">Vital Signs Intake</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <label class="block font-medium text-gray-700">Blood Pressure (mmHg)</label>
                            <input type="text" name="bp" placeholder="120/80" class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700">Temperature (°C)</label>
                            <input type="number" step="0.1" name="temp" placeholder="36.5" class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700">Pulse Rate (bpm)</label>
                            <input type="number" name="pulse" placeholder="72" class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700">SpO₂ (%)</label>
                            <input type="number" name="spo2" placeholder="98" class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight" placeholder="70" class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700">Height (cm)</label>
                            <input type="number" step="0.1" name="height" placeholder="170" class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Priority & Complaints -->
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="block font-medium text-gray-700">Triage Priority Level *</label>
                        <select name="priority" required class="w-full border-gray-300 rounded-lg p-2 font-bold text-gray-800">
                            <option value="Standard" selected>Standard (Routine OPD)</option>
                            <option value="Urgent">Urgent (Requires prompt attention)</option>
                            <option value="Very Urgent">Very Urgent (Severe symptoms)</option>
                            <option value="Emergency" class="text-red-600 font-bold">Emergency (Immediate resuscitation required)</option>
                            <option value="Non-Urgent">Non-Urgent (Minor illness/refill)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700">Chief Complaint / Primary Symptoms *</label>
                        <textarea name="chief_complaint" rows="3" required placeholder="Describe what brought the patient in today..." 
                            class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t">
                    <a href="{{ route('triage.index') }}" class="text-gray-500 hover:text-gray-700 font-bold text-sm">
                        ← Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-lg transition shadow">
                        Save Vitals & Send to Doctor Queue →
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
