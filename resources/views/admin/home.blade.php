<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 CEO Dashboard & Analytics
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

                <div class="bg-green-100 border-l-4 border-green-500 p-6 rounded shadow-sm">
                    <p class="text-green-700 font-bold uppercase text-xs">Total Revenue</p>
                    <h3 class="text-3xl font-bold text-gray-800">KES {{ number_format($total_revenue) }}</h3>
                </div>

                <div class="bg-blue-100 border-l-4 border-blue-500 p-6 rounded shadow-sm">
                    <p class="text-blue-700 font-bold uppercase text-xs">Active Doctors</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $total_doctors }}</h3>
                </div>

                <div class="bg-purple-100 border-l-4 border-purple-500 p-6 rounded shadow-sm">
                    <p class="text-purple-700 font-bold uppercase text-xs">Registered Patients</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $total_patients }}</h3>
                </div>

                <div class="bg-yellow-100 border-l-4 border-yellow-500 p-6 rounded shadow-sm">
                    <p class="text-yellow-700 font-bold uppercase text-xs">Total Visits</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $total_appointments }}</h3>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">📅 Appointment Status</h3>
                    <canvas id="statusChart"></canvas>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">⚡ Quick Management</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ url('show_employees') }}"
                            class="block p-4 border rounded hover:bg-gray-50 flex items-center mt-4">
                            <span
                                class="bg-purple-500 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">👥</span>
                            <div>
                                <h4 class="font-bold">Manage Staff</h4>
                                <p class="text-xs text-gray-500">View, Edit or Delete Employees</p>
                            </div>
                        </a>
                        <a href="{{ url('add_doctor_view') }}"
                            class="block p-4 border rounded hover:bg-gray-50 flex items-center">
                            <span
                                class="bg-blue-500 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">+</span>
                            <div>
                                <h4 class="font-bold">Add New Employee</h4>
                                <p class="text-xs text-gray-500">Register Doctors, Nurses, Staff</p>
                            </div>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // We capture the data in variables first to prevent syntax errors
        var approved = {{ $status_counts['approved'] }};
        var canceled = {{ $status_counts['canceled'] }};
        var completed = {{ $status_counts['completed'] }};

        const ctx = document.getElementById('statusChart');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Canceled', 'Completed'],
                datasets: [{
                    label: '# of Appointments',
                    data: [approved, canceled, completed], // Use variables here
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)', // Blue
                        'rgba(255, 99, 132, 0.6)', // Red
                        'rgba(75, 192, 192, 0.6)' // Green
                    ],
                    borderWidth: 1
                }]
            }
        });
    </script>
</x-app-layout>
