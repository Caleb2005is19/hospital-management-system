<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏥 Reception & Patient Registration
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        <!-- Grid: Search/List (Left) & New Patient Registration (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT: Search & Patient List (2 cols) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">🔍 Search Existing Patient</h3>
                
                <form method="GET" action="{{ route('patients.index') }}" class="flex gap-2 mb-6">
                    <input type="text" name="search" value="{{ $query }}" placeholder="Search by ID, Name, Phone, or Patient No..."
                        class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded-lg transition">
                        Search
                    </button>
                    @if($query)
                        <a href="{{ route('patients.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-4 py-2 rounded-lg transition flex items-center">
                            Clear
                        </a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="py-3 px-4 text-left">Patient ID / Name</th>
                                <th class="py-3 px-4 text-left">Contact</th>
                                <th class="py-3 px-4 text-left">Gender/Age</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($patients as $patient)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 font-medium text-gray-900">
                                        <span class="text-blue-600 font-bold block">{{ $patient->patient_number }}</span>
                                        {{ $patient->name }}
                                        @if($patient->national_id)
                                            <span class="text-xs text-gray-400 block">ID: {{ $patient->national_id }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ $patient->phone }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ $patient->gender }} 
                                        @if($patient->dob)
                                            ({{ \Carbon\Carbon::parse($patient->dob)->age }} yrs)
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <!-- Quick New Encounter Form -->
                                        <form method="POST" action="{{ route('patients.encounter', $patient->id) }}" class="flex items-center justify-center gap-1">
                                            @csrf
                                            <select name="encounter_type" class="text-xs border-gray-300 rounded p-1">
                                                <option value="OPD">OPD</option>
                                                <option value="IPD">IPD</option>
                                                <option value="ER">ER</option>
                                            </select>
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold text-xs px-3 py-1.5 rounded transition">
                                                + New Visit
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-gray-400">
                                        No patients found. Register a new patient on the right.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $patients->links() }}
                </div>
            </div>

            <!-- RIGHT: Register New Patient Form (1 col) -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">👤 Register New Patient</h3>

                <form method="POST" action="{{ route('patients.store') }}" class="space-y-4 text-sm">
                    @csrf
                    <div>
                        <label class="block font-medium text-gray-700">Full Name *</label>
                        <input type="text" name="name" required class="w-full border-gray-300 rounded-lg p-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-medium text-gray-700">National ID</label>
                            <input type="text" name="national_id" class="w-full border-gray-300 rounded-lg p-2">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700">Phone *</label>
                            <input type="text" name="phone" required class="w-full border-gray-300 rounded-lg p-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-medium text-gray-700">Gender *</label>
                            <select name="gender" required class="w-full border-gray-300 rounded-lg p-2">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="dob" class="w-full border-gray-300 rounded-lg p-2">
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700">Visit Type *</label>
                        <select name="encounter_type" required class="w-full border-gray-300 rounded-lg p-2 bg-blue-50 font-bold text-blue-700">
                            <option value="OPD">OPD (Outpatient)</option>
                            <option value="ER">ER (Emergency)</option>
                            <option value="IPD">IPD (Inpatient Direct)</option>
                        </select>
                    </div>
                  <!-- Address Input -->
<div>
    <label class="block font-medium text-gray-700">Residential Address / Estate</label>
    <input type="text" name="address" value="{{ old('address') }}" placeholder="e.g. Westlands, Nairobi" 
        class="w-full border-gray-300 rounded-lg p-2 focus:ring-blue-500">
</div>


                    <div>
                        <label class="block font-medium text-gray-700">Known Allergies</label>
                        <input type="text" name="allergies" placeholder="e.g. Penicillin, Nuts" class="w-full border-gray-300 rounded-lg p-2">
                    </div>

                    <hr class="my-2">
                    <p class="text-xs font-bold text-gray-500 uppercase">Next of Kin</p>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <input type="text" name="next_of_kin_name" placeholder="Contact Name" class="w-full border-gray-300 rounded-lg p-2 text-xs">
                        </div>
                        <div>
                            <input type="text" name="next_of_kin_phone" placeholder="Contact Phone" class="w-full border-gray-300 rounded-lg p-2 text-xs">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition shadow">
                        Register & Queue Patient
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
