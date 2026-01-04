<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🩺 Triage Department (Vitals Station)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4 text-purple-700">Waiting for Vitals</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($patients as $patient)
                    <div class="border border-purple-200 rounded-lg p-4 bg-purple-50">
                        <div class="flex justify-between mb-2">
                            <span class="font-bold text-lg">{{ $patient->name }}</span>
                            <span class="text-xs bg-purple-200 text-purple-800 px-2 py-1 rounded">#{{ $patient->id }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Walk-in Admission</p>

                        <form action="{{ url('submit_triage', $patient->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-3 gap-2 mb-2">
                                <input type="text" name="bp" placeholder="BP (120/80)" class="text-xs border rounded p-1" required>
                                <input type="text" name="temp" placeholder="Temp (36.5)" class="text-xs border rounded p-1" required>
                                <input type="text" name="weight" placeholder="Kg" class="text-xs border rounded p-1" required>
                            </div>

                            <textarea name="notes" placeholder="Nurse Observation..." class="w-full text-xs border rounded p-1 mb-2"></textarea>

                            <label class="block text-xs font-bold mb-1">Send Patient To:</label>
                            <div class="flex gap-2">
                                <button type="submit" name="destination" value="Doctor" class="flex-1 bg-blue-600 hover:bg-blue-800 text-white text-xs font-bold py-2 rounded">
                                    👨‍⚕️ Doctor
                                </button>
                                <button type="submit" name="destination" value="OPD" class="flex-1 bg-green-600 hover:bg-green-800 text-white text-xs font-bold py-2 rounded">
                                    🏥 OPD / Ward
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach

                    @if($patients->isEmpty())
                    <p class="text-gray-500 italic">No patients waiting for triage.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>