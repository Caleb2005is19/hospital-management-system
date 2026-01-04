<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-t-8 border-purple-600">

                <h2 class="text-2xl font-bold mb-6 text-gray-800">🛏️ Assign Bed to Patient</h2>

                <div class="bg-gray-50 p-4 rounded mb-6 border border-gray-200">
                    <p class="text-sm text-gray-500 uppercase font-bold">Patient Name</p>
                    <p class="text-xl font-bold mb-2">{{ $appointment->patient->name }}</p>

                    <p class="text-sm text-gray-500 uppercase font-bold">Doctor's Diagnosis</p>
                    <p class="text-md mb-2">{{ $appointment->reason }}</p>

                    <p class="text-sm text-gray-500 uppercase font-bold">Required Ward</p>
                    <span class="bg-purple-100 text-purple-800 py-1 px-3 rounded-full text-sm font-bold">
                        {{ $appointment->target_ward }}
                    </span>
                </div>

                @if($available_beds->isEmpty())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    ⚠️ No beds available in {{ $appointment->target_ward }}! Please add more beds first.
                </div>
                @else
                <form action="{{ url('assign_bed_store', $appointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $appointment->patient->id }}">

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Select Available Bed</label>
                        <select name="bed_id" class="w-full border rounded p-3 text-lg bg-white shadow-sm" required>
                            @foreach($available_beds as $bed)
                            <option value="{{ $bed->id }}">
                                {{ $bed->bed_number }} (Available)
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-800 text-white font-bold py-3 rounded text-lg shadow">
                        ✅ Confirm Admission
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>