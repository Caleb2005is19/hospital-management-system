<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Record Vitals: {{ $patient->name }}</h2>

                <form action="{{ url('store_vitals', $patient->id) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Temperature (°C)</label>
                            <input type="text" name="temperature" class="border rounded w-full py-2 px-3" placeholder="37.5">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Blood Pressure</label>
                            <input type="text" name="blood_pressure" class="border rounded w-full py-2 px-3" placeholder="120/80">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Heart Rate (bpm)</label>
                        <input type="text" name="heart_rate" class="border rounded w-full py-2 px-3" placeholder="72">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nurse's Observation Note</label>
                        <textarea name="nurse_note" class="border rounded w-full py-2 px-3" rows="3"></textarea>
                    </div>

                    <button type="submit" class="bg-pink-500 text-white font-bold py-2 px-4 rounded hover:bg-pink-700 w-full">
                        💾 Save Vitals
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>