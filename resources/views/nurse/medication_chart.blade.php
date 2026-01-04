<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                💊 Medication Administration Record (MAR)
            </h2>
            <a href="{{ url('/home') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-sm rounded-lg mb-6 border-l-4 border-blue-500">
                <div class="flex justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $appointment->patient->name }}</h1>
                        <p class="text-gray-600">ID: #{{ $appointment->patient->id }} | Ward: {{ $appointment->patient->bed->ward_name ?? 'Not Assigned' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-500">Diagnosis</p>
                        <p class="text-lg">{{ $appointment->reason }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Scheduled Medications</h3>

                    <table class="min-w-full border-collapse border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-3 text-left">Drug Name</th>
                                <th class="border p-3 text-left">Dosage</th>
                                <th class="border p-3 text-left">Frequency</th>
                                <th class="border p-3 text-left">Duration</th>
                                <th class="border p-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meds as $med)
                            <tr class="hover:bg-gray-50">
                                <td class="border p-3 font-bold text-blue-700">{{ $med->drug_name }}</td>
                                <td class="border p-3">{{ $med->dosage }}</td>
                                <td class="border p-3">{{ $med->frequency }}</td>
                                <td class="border p-3">{{ $med->duration }}</td>
                                <td class="border p-3 text-center">
                                    <form action="{{ url('administer_drug', $med->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-4 rounded text-sm shadow">
                                            ✅ Give Dose
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-500">🕒 Administration History (Log)</h3>
                <ul class="list-disc ml-5">
                    @foreach($history as $log)
                    <li class="mb-1 text-sm text-gray-700">
                        <strong>{{ $log->status }}</strong> at {{ $log->created_at->format('H:i') }}
                        - <span class="text-gray-500">By Nurse {{ $log->nurse->name ?? 'Unknown' }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>