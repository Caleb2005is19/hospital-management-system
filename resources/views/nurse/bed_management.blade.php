<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 text-blue-800">🛏️ Ward Setup (Add Beds)</h3>

                @if(session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session()->get('message') }}
                </div>
                @endif

                <form action="{{ url('store_bed') }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Select Ward</label>
                        <select name="ward_name" class="border rounded p-2 w-48">
                            <option>General Ward</option>
                            <option>ICU</option>
                            <option>Maternity</option>
                            <option>Pediatrics</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Bed Number</label>
                        <input type="text" name="bed_number" placeholder="e.g. Bed-104" class="border rounded p-2 w-48" required>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                        + Add Bed
                    </button>
                </form>
            </div>
            <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border border-yellow-200">
                <h3 class="text-lg font-bold mb-4 text-yellow-800">⏳ Patients Waiting for Admission</h3>

                @php
                // Fetch pending patients directly in view for simplicity (or pass via controller)
                $pending = App\Models\Appointment::where('status', 'approved')
                ->whereNotNull('target_ward')
                ->get();
                @endphp

                @if($pending->isEmpty())
                <p class="text-gray-500 text-sm">No pending admissions.</p>
                @else
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-yellow-100">
                        <tr>
                            <th class="py-2 px-4 text-left text-xs font-bold text-gray-600">Patient</th>
                            <th class="py-2 px-4 text-left text-xs font-bold text-gray-600">Diagnosis</th>
                            <th class="py-2 px-4 text-left text-xs font-bold text-gray-600">Target Ward</th>
                            <th class="py-2 px-4 text-left text-xs font-bold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $p)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $p->patient->name }}</td>
                            <td class="py-2 px-4">{{ $p->reason }}</td>
                            <td class="py-2 px-4 font-bold text-blue-600">{{ $p->target_ward }}</td>
                            <td class="py-2 px-4">
                                <a href="{{ url('bed_assign_view', $p->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-xs font-bold hover:bg-blue-700">
                                    Assign Bed
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-700">🏥 Ward Status Overview</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($beds as $bed)
                    <div class="border rounded-lg p-4 text-center {{ $bed->status == 'Available' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">

                        <div class="text-2xl mb-1">🛏️</div>
                        <h4 class="font-bold">{{ $bed->bed_number }}</h4>
                        <p class="text-xs text-gray-500">{{ $bed->ward_name }}</p>

                        @if($bed->status == 'Available')
                        <span class="text-xs font-bold text-green-700 block mt-2">Available</span>
                        @else
                        <span class="text-xs font-bold text-red-700 block mt-2">Occupied</span>
                        <p class="text-xs">ID: {{ $bed->patient_id }}</p>
                        <a href="{{ url('discharge_bed', $bed->id) }}" class="text-xs underline text-blue-600 mt-1 block">Discharge</a>
                        @endif

                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>