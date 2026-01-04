<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👩‍⚕️ Nurse Station: <span class="text-blue-600">{{ Auth::user()->department ?? 'General Pool' }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Patient Monitoring List</h1>

                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reason</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appoint)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex items-center">
                                        <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap font-bold">
                                                {{ $appoint->patient->name }}
                                            </p>
                                            <p class="text-gray-600 text-xs">ID: #{{ $appoint->patient->id }}</p>

                                            @php
                                            // Find if patient is in a bed
                                            $currentBed = \App\Models\Bed::where('patient_id', $appoint->patient->id)->first();
                                            @endphp

                                            @if($currentBed)
                                            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                🏥 {{ $currentBed->ward_name }} : {{ $currentBed->bed_number }}
                                            </span>
                                            @else
                                            <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="text-xs font-bold text-yellow-800 uppercase flex items-center">
                                                        ⚠️ Admission Request
                                                    </span>
                                                </div>

                                                <div class="mb-3 text-xs text-gray-700 bg-white p-2 rounded border border-yellow-100">
                                                    <p class="mb-1">
                                                        <span class="font-bold text-gray-900">Diagnosis:</span>
                                                        {{ $appoint->reason }}
                                                    </p>
                                                    <p>
                                                        <span class="font-bold text-gray-900">Doctor's Orders:</span>
                                                        {{ Str::limit($appoint->prescription, 100) }}
                                                    </p>
                                                </div>

                                                <form action="{{ url('assign_bed', $appoint->patient->id) }}" method="POST" class="flex flex-col gap-2">
                                                    @csrf
                                                    <div class="flex gap-2">
                                                        <select name="bed_id" class="text-xs border rounded p-1 flex-grow bg-white">
                                                            <option value="">Select Bed...</option>
                                                            @foreach(\App\Models\Bed::where('ward_name', Auth::user()->department)->whereNull('patient_id')->get() as $bed)
                                                            <option value="{{ $bed->id }}">{{ $bed->bed_number }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="bg-gray-800 hover:bg-black text-white text-xs px-3 py-1 rounded font-bold shadow">
                                                            Accept
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $appoint->reason }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ url('add_vitals', $appoint->patient->id) }}" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-1 px-3 rounded text-xs">
                                            🌡️ Vitals
                                        </a>

                                        <a href="{{ url('medication_chart', $appoint->id) }}" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs">
                                            💊 Med Chart
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>