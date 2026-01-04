<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-blue-50 border-l-4 border-blue-500">
                    <h3 class="text-lg font-bold text-gray-900">💊 Doctor's Prescription</h3>
                    <p class="text-sm text-gray-600 mb-2">Prescribed by Dr. {{ $appointment->doctor_id ?? 'Unknown' }} on {{ $appointment->date }}</p>

                    <div class="p-4 bg-white border rounded shadow-inner">
                        <p class="text-xl font-mono text-gray-800">
                            {{ $appointment->prescription ?? 'No prescription written yet.' }}
                        </p>
                    </div>

                    <form action="{{ url('mark_medication_given', $appointment->id) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-700">Nurse Notes</label>
                                <input type="text" name="remarks" class="w-full border rounded p-2" placeholder="e.g. Given after lunch...">
                            </div>
                            <button type="submit" class="bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-800">
                                ✅ Mark as Given
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">📈 Vitals History</h3>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-5 py-3 border-b-2">Date</th>
                                <th class="px-5 py-3 border-b-2">Temp</th>
                                <th class="px-5 py-3 border-b-2">BP</th>
                                <th class="px-5 py-3 border-b-2">HR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vitals as $vital)
                            <tr>
                                <td class="px-5 py-2 border-b">{{ $vital->created_at->format('d M H:i') }}</td>
                                <td class="px-5 py-2 border-b">{{ $vital->temperature }}</td>
                                <td class="px-5 py-2 border-b">{{ $vital->blood_pressure }}</td>
                                <td class="px-5 py-2 border-b">{{ $vital->heart_rate }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-6 shadow-sm rounded-lg border-l-4 border-purple-500">
            <h3 class="text-lg font-bold mb-4">✍️ Add Progress Note</h3>
            <form action="{{ url('store_nursing_note', $appointment->patient_id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700">Note Type</label>
                    <select name="type" class="w-full border rounded p-2 bg-gray-50">
                        <option value="Routine">Routine Check</option>
                        <option value="Incident">⚠️ Incident / Complaint</option>
                        <option value="Doctor Visit">👨‍⚕️ Doctor Round</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700">Observation</label>
                    <textarea name="note" rows="4" class="w-full border rounded p-2" placeholder="e.g. Patient resting comfortably..."></textarea>
                </div>

                <button type="submit" class="w-full bg-purple-600 text-white font-bold py-2 px-4 rounded hover:bg-purple-800">
                    Post Note
                </button>
            </form>
        </div>

        <div class="md:col-span-2 bg-white p-6 shadow-sm rounded-lg">
            <h3 class="text-lg font-bold mb-4">📅 Patient Progress Timeline</h3>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach($notes as $note)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full 
                        @if($note->type == 'Incident') bg-red-100 text-red-600 
                        @elseif($note->type == 'Doctor Visit') bg-blue-100 text-blue-600 
                        @else bg-gray-100 text-gray-600 @endif">
                            @if($note->type == 'Incident') ⚠️ @elseif($note->type == 'Doctor Visit') 👨‍⚕️ @else 📝 @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-bold text-gray-900">
                            {{ $note->type }}
                            <span class="text-xs font-normal text-gray-500">• {{ $note->created_at->diffForHumans() }}</span>
                        </p>
                        <p class="text-gray-700 mt-1">{{ $note->note }}</p>
                        <p class="text-xs text-gray-400 mt-1">Logged by Nurse #{{ $note->nurse_id }}</p>
                    </div>
                </div>
                <hr class="border-gray-100">
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>