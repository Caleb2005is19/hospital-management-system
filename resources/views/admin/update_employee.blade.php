<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Edit Employee Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-lg mx-auto">

                <form action="{{ url('edit_employee', $user->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                        <input type="text" name="name" value="{{ $user->name }}"
                            class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}"
                            class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ $user->phone }}"
                            class="w-full border rounded p-2" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                            <select name="usertype" class="w-full border rounded p-2 bg-white">
                                <option value="{{ $user->usertype }}">{{ ucfirst($user->usertype) }} (Current)</option>
                                <option value="doctor">Doctor</option>
                                <option value="nurse">Nurse</option>
                                <option value="receptionist">Receptionist</option>
                                <option value="pharmacist">Pharmacist</option>
                                <option value="cashier">Cashier</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Department</label>
                            <input type="text" name="department" value="{{ $user->department }}"
                                class="w-full border rounded p-2">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Room Number</label>
                        <input type="text" name="room" value="{{ $user->room }}"
                            class="w-full border rounded p-2">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                        💾 Update Details
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
