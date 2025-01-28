<x-app-layout>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="w-full h-screen bg-white shadow-2xl overflow-hidden">
            <div class="p-10 h-full">
                @foreach (['error' => 'danger', 'success' => 'success'] as $key => $type)
                    @if (session()->has($key))
                        <div class="alert alert-{{ $type }}" role="alert">
                            <strong class="font-bold">{{ session()->get($key) }}</strong>
                        </div>
                    @endif
                @endforeach
                @if (auth()->user()->isAdmin)
                    <div class="overflow-auto" style="height:100%">
                        <table class="w-full divide-y divide-gray-200 table-auto shadow-md">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Times
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        One Time
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Durations
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-100">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                            {{ $user->times ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                            {{ $user->run_one_time ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                            {{ $user->durations->time_value ?? '' }}
                                            {{ $user->durations->time_unit ?? '' }}
                                            {{ isset($user->durations->start_time) ? $user->durations->start_time->format('H:i') : '' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                            <div class="relative inline-block text-left">
                                                <div>
                                                    <button type="button"
                                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        id="options-menu-{{ $user->id }}"
                                                        onclick="toggleDropdown({{ $user->id }})">
                                                        Actions
                                                        <svg class="-mr-1 ml-2 h-5 w-5"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div id="dropdown-{{ $user->id }}"
                                                    class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50">
                                                    <div class="py-1">
                                                        <button onclick="openTimesModal({{ $user->id }})"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                            User Based Times
                                                        </button>
                                                        <a href="{{ route('one.time.dashboard', $user->id) }}"
                                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                            One Time API
                                                        </a>
                                                        <button onclick="openDurationsModal({{ $user->id }})"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                            Based Durations
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal for User Based Times -->
                                            <div id="times-modal-{{ $user->id }}"
                                                class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                                <div
                                                    class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                                    <div class="mt-3">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Enter Times</h3>
                                                        <form action="{{ route('users.update.times', $user->id) }}"
                                                            method="POST" class="mt-4">
                                                            @csrf
                                                            <div>
                                                                <label for="times-{{ $user->id }}"
                                                                    class="block text-sm font-medium text-gray-700">Number
                                                                    of Times</label>
                                                                <input type="number" id="times-{{ $user->id }}"
                                                                    name="times" min="1" step="1"
                                                                    required
                                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value < 1) this.value = 1;">
                                                            </div>
                                                            <div class="mt-4 flex justify-end space-x-3">
                                                                <button type="button"
                                                                    onclick="closeTimesModal({{ $user->id }})"
                                                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                    Cancel
                                                                </button>
                                                                <button type="submit"
                                                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                    Save
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Add Durations Modal -->
                                            <div id="durations-modal-{{ $user->id }}"
                                                class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                                <div
                                                    class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                                    <div class="mt-3">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">Set
                                                            Durations</h3>
                                                        <form action="{{ route('duration.dashboard', $user->id) }}"
                                                            method="POST" class="mt-4">
                                                            @csrf
                                                            <div class="space-y-4">
                                                                <!-- Start Time -->
                                                                <div>
                                                                    <label for="start_time-{{ $user->id }}"
                                                                        class="block text-sm font-medium text-gray-700">
                                                                        Start Time
                                                                    </label>
                                                                    <input type="datetime-local"
                                                                        id="start_time-{{ $user->id }}"
                                                                        name="start_time" value="" required
                                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                                </div>

                                                                <!-- Time Unit -->
                                                                <div>
                                                                    <label for="time_unit-{{ $user->id }}"
                                                                        class="block text-sm font-medium text-gray-700">
                                                                        Time Unit
                                                                    </label>
                                                                    <select id="time_unit-{{ $user->id }}"
                                                                        name="time_unit" required
                                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                                        <option value="" disabled selected>
                                                                            Select Time Unit</option>
                                                                        <option value="minutes">Minutes</option>
                                                                        <option value="hours">Hours</option>
                                                                        <option value="days">Days</option>
                                                                        <option value="months">Months</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Time Value -->
                                                                <div>
                                                                    <label for="time_value-{{ $user->id }}"
                                                                        class="block text-sm font-medium text-gray-700">
                                                                        Time Value
                                                                    </label>
                                                                    <input type="number"
                                                                        id="time_value-{{ $user->id }}"
                                                                        name="time_value" min="1" required
                                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                                </div>
                                                            </div>

                                                            <!-- Modal Buttons -->
                                                            <div class="mt-4 flex justify-end space-x-3">
                                                                <button type="button"
                                                                    onclick="closeDurationsModal({{ $user->id }})"
                                                                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                                    Cancel
                                                                </button>
                                                                <button type="submit"
                                                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                                    Save
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        <nav class="flex justify-between items-center">
                            {{ $users->links('pagination::tailwind') }}
                        </nav>
                    </div>
                @else
                    <div class="overflow-x-auto" style="height:100%">
                        <div>
                            @if (Auth::user()->one_time == 1)
                                <button
                                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <a href="{{ route('one.time') }}">
                                        One Time
                                    </a>
                                </button>
                            @endif
                            @if (Auth::user()->times > 0)
                                <button
                                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <a href="{{ route('times') }}">
                                        Times
                                    </a>
                                </button>
                            @endif
                            @if (Auth::user()->has('durations'))
                                <button
                                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <a href="{{ route('duration') }}">
                                        Duration
                                    </a>
                                </button>
                            @endif
                        </div>
                    </div>
            </div>
            @endif
        </div>
    </div>
    </div>
    </div>
</x-app-layout>


<script>
    function toggleDropdown(userId) {
        const dropdown = document.getElementById(`dropdown-${userId}`);
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');

        allDropdowns.forEach(dd => {
            if (dd.id !== `dropdown-${userId}`) {
                dd.classList.add('hidden');
            }
        });

        dropdown.classList.toggle('hidden');
    }

    function openTimesModal(userId) {
        const modal = document.getElementById(`times-modal-${userId}`);
        modal.classList.remove('hidden');
        // Close dropdown when opening modal
        document.getElementById(`dropdown-${userId}`).classList.add('hidden');
    }

    function closeTimesModal(userId) {
        const modal = document.getElementById(`times-modal-${userId}`);
        modal.classList.add('hidden');
        // Reset input value
        document.getElementById(`times-${userId}`).value = '';
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.relative')) {
            const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
            allDropdowns.forEach(dd => dd.classList.add('hidden'));
        }
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('[id^="times-modal-"]');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>


<script>
    // Add new duration modal functions
    function openDurationsModal(userId) {
        const modal = document.getElementById(`durations-modal-${userId}`);
        modal.classList.remove('hidden');
        document.getElementById(`dropdown-${userId}`).classList.add('hidden');
    }

    function closeDurationsModal(userId) {
        const modal = document.getElementById(`durations-modal-${userId}`);
        modal.classList.add('hidden');
    }

    // Update window click handler to close duration modals
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('[id^="times-modal-"], [id^="durations-modal-"]');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
