<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    @foreach (['error' => 'danger', 'success' => 'success'] as $key => $type)
                        @if (session()->has($key))
                            <div class="alert alert-{{ $type }}" role="alert">
                                <strong class="font-bold">{{ session()->get($key) }}</strong>
                            </div>
                        @endif
                    @endforeach
                    @if (auth()->user()->isAdmin)
                        <div class="overflow-x-auto">
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
                                                {{ $user->times }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                                <form action="{{ route('users.update.times', $user->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- Flex container for input and button -->
                                                        <input type="number"
                                                            class="w-12 px-2 py-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-center"
                                                            name="times" min="1" step="1">
                                                        <button type="submit"
                                                            style="background-color: #4f46e5; color: #fff; height: 35px; margin-left: 10px;"
                                                            class="btn px-4 py-2 rounded-md flex items-center justify-center">
                                                            Save
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>

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
                        <div class="overflow-x-auto">
                            <button class="btn px-4 py-2 rounded-md" style="background-color: #4f46e5; color: #fff;">
                                <a href="{{ route('users.times') }}">GO</a>
                            </button>
                            <label class="text-gray-600"> Available Times: {{ auth()->user()->times }}</label>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
