<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 text-gray-900">
        <h3 class="text-lg font-bold mb-4">📅 Book an Appointment</h3>

        @if(session()->has('message'))
        <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session()->get('message') }}
        </div>
        @endif

        <form action="{{ url('upload_appointment') }}" method="POST">
            @csrf <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                    <input type="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Time</label>
                    <input type="time" name="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Reason for Visit</label>
                <textarea name="reason" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="e.g. Fever, Headache..." required></textarea>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Submit Request
                </button>
            </div>
        </form>
        <hr class="my-8 border-gray-300">

        <h3 class="text-lg font-bold mb-4">📂 My Medical History</h3>

        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reason</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prescription</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appoint)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{ $appoint->date }}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{ $appoint->reason }}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($appoint->status == 'completed') bg-blue-100 text-blue-800 
                        @elseif($appoint->status == 'approved') bg-green-100 text-green-800 
                        @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($appoint->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        @if($appoint->status == 'completed')
                        <p class="text-gray-500 italic mb-2">{{ Str::limit($appoint->prescription, 20) }}...</p>
                        <a href="{{ url('print_pdf', $appoint->id) }}" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-xs">
                            🖨️ Print PDF
                        </a>
                        @else
                        <span class="text-gray-400">Waiting for Doctor...</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>