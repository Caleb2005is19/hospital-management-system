<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 Shift Handover Board
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                <h3 class="text-lg font-bold mb-4 text-purple-700">✍️ End of Shift Entry</h3>

                <form action="{{ url('store_shift_report') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Current Shift</label>
                        <select name="shift_type" class="w-full border rounded p-2">
                            <option value="Day Shift (7AM - 7PM)">☀️ Day Shift</option>
                            <option value="Night Shift (7PM - 7AM)">🌙 Night Shift</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Handover Notes</label>
                        <textarea name="notes" rows="6" class="w-full border rounded p-2" placeholder="Summary of critical events, pending tasks, or issues..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded">
                        📢 Post Report
                    </button>
                </form>
            </div>

            <div class="md:col-span-2 space-y-4">
                <h3 class="text-lg font-bold mb-4 text-gray-700">🕒 Recent Handover Logs</h3>

                @foreach($reports as $report)
                <div class="bg-white shadow-sm rounded-lg p-6 border-l-4 
                    {{ $report->shift_type == 'Night Shift (7PM - 7AM)' ? 'border-blue-800 bg-blue-50' : 'border-yellow-400' }}">

                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <div class="font-bold text-lg mr-2">{{ $report->nurse->name }}</div>
                            <span class="text-xs px-2 py-1 rounded-full text-white 
                                {{ $report->shift_type == 'Night Shift (7PM - 7AM)' ? 'bg-blue-800' : 'bg-yellow-500' }}">
                                {{ $report->shift_type }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">{{ $report->created_at->format('d M, h:i A') }}</div>
                    </div>

                    <p class="text-gray-800 whitespace-pre-wrap">{{ $report->notes }}</p>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>