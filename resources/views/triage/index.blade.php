<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🩺 Nurse Triage Queue
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Patients Waiting for Triage ({{ $encounters->count() }})</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="py-3 px-4 text-left">Time Queued</th>
                            <th class="py-3 px-4 text-left">Encounter No.</th>
                            <th class="py-3 px-4 text-left">Patient Name</th>
                            <th class="py-3 px-4 text-left">Visit Type</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($encounters as $encounter)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-gray-500">
                                    {{ $encounter->created_at->diffForHumans() }}
                                </td>
                                <td class="py-3 px-4 font-bold text-blue-600">
                                    {{ $encounter->encounter_number }}
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-900">
                                    {{ $encounter->patient->name }}
                                    <span class="text-xs text-gray-400 block">{{ $encounter->patient->patient_number }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full 
                                        {{ $encounter->type === 'ER' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $encounter->type }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('triage.create', $encounter->id) }}" 
                                       class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2 rounded-lg transition shadow">
                                        Take Vitals 🩺
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400">
                                    No patients currently waiting in the triage queue.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
