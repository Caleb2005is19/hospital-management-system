<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👥 Employee Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session()->get('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session()->get('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-700">Current Staff List</h3>
                    <a href="{{ url('add_doctor_view') }}"
                        class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded text-sm">
                        + Add New Employee
                    </a>
                </div>

                <table class="min-w-full leading-normal">
                    <thead>
                        <tr
                            class="bg-gray-100 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="px-5 py-3">Name</th>
                            <th class="px-5 py-3">Role</th>
                            <th class="px-5 py-3">Department</th>
                            <th class="px-5 py-3">Contact</th>
                            <th class="px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $employee->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $employee->email }}</p>
                                </td>
                                <td class="px-5 py-5 text-sm">
                                    <span
                                        class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                        <span aria-hidden="true"
                                            class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                        <span class="relative uppercase text-xs">{{ $employee->usertype }}</span>
                                    </span>
                                </td>
                                <td class="px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $employee->department ?? 'N/A' }}</p>
                                    <p class="text-gray-500 text-xs">Room: {{ $employee->room ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $employee->phone }}</p>
                                </td>
                                <td class="px-5 py-5 text-sm flex gap-2">
                                    <a href="{{ url('edit_employee_view', $employee->id) }}"
                                        class="text-blue-600 hover:text-blue-900 font-bold">Edit</a>
                                    <a href="{{ url('delete_employee', $employee->id) }}"
                                        onclick="return confirm('Are you sure you want to fire this employee?')"
                                        class="text-red-600 hover:text-red-900 font-bold ml-2">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
