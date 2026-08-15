<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👨‍⚕️ Doctor Consultation Queue
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Patients Waiting in Consultation Queue ({{ $encounters->count() }})</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="py-3 px-4 text-left">Priority</th>
                            <th class="py-3 px-4 text-left">Encounter No.</th>
                            <th class="py-3 px-4 text-left">Patient Details</th>
                            <th class="py-3 px-4 text-left">Triage Vitals Summary</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($encounters as $encounter)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full
                                        {{ $encounter->triage?->priority === 'Emergency' ? 'bg-red-200 text-red-800' : 
                                          ($encounter->triage?->priority === 'Urgent' ? 'bg-orange-200 text-orange-800' : 'bg-gray-100 text-gray-700') }}">
                                        {{ $encounter->triage?->priority ?? 'Standard' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-bold text-blue-600">
                                    {{ $encounter->encounter_number }}
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-900">
                                    {{ $encounter->patient->name }}
                                    <span class="text-xs text-gray-400 block">{{ $encounter->patient->patient_number }} | {{ $encounter->patient->gender }}</span>
                                </td>
                                <td class="py-3 px-4 text-gray-600 text-xs">
                                    @if($encounter->triage)
                                        <div class="space-y-0.5">
                                            <div><strong>BP:</strong> {{ $encounter->triage->bp ?? 'N/A' }} | <strong>Temp:</strong> {{ $encounter->triage->temp ? $encounter->triage->temp . '°C' : 'N/A' }}</div>
                                            <div><strong>PR:</strong> {{ $encounter->triage->pulse ?? 'N/A' }} bpm | <strong>SpO2:</strong> {{ $encounter->triage->spo2 ? $encounter->triage->spo2 . '%' : 'N/A' }}</div>
                                            <div class="text-gray-500 italic">"{{ Str::limit($encounter->triage->chief_complaint, 40) }}"</div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">No triage recorded</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('doctor.consult', $encounter->id) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2 rounded-lg transition shadow">
                                        Start Consult 🩺
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400">
                                    No patients currently queued for consultation.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
