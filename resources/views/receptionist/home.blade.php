<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📂 Records & Admissions Desk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                <h3 class="text-lg font-bold mb-4 text-purple-700">👤 New Patient Registration</h3>

                @if(session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session()->get('message') }}
                    </div>
                @endif
                
                <form action="{{ url('register_patient_record') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Patient Name</label>
                        <input type="text" name="name" class="w-full border rounded p-2" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                            <input type="number" name="phone" class="w-full border rounded p-2" required placeholder="Used as Password">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" name="email" class="w-full border rounded p-2" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Physical Address</label>
                        <textarea name="address" class="w-full border rounded p-2"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded shadow">
                        💾 Create File
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-700">🕒 Recently Registered</h3>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">File ID</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                        <tr class="border-b">
                            <td class="px-3 py-2 text-sm font-bold">#{{ $patient->id }}</td>
                            <td class="px-3 py-2 text-sm">{{ $patient->name }}</td>
                            <td class="px-3 py-2 text-sm text-gray-600">{{ $patient->phone }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>