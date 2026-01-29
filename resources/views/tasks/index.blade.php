<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tasks') }}
            </h2>
            <a href="{{ route('tasks.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Create Task') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b dark:border-gray-700">
                                    <th class="py-3 px-4 font-semibold text-sm">{{ __('Title') }}</th>
                                    <th class="py-3 px-4 font-semibold text-sm">{{ __('Status') }}</th>
                                    <th class="py-3 px-4 font-semibold text-sm">{{ __('Priority') }}</th>
                                    <th class="py-3 px-4 font-semibold text-sm">{{ __('Assigned') }}</th>
                                    <th class="py-3 px-4 font-semibold text-sm">{{ __('Deadline') }}</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-right">{{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr
                                        class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                        <td class="py-3 px-4">
                                            <a href="{{ route('tasks.show', $task) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                {{ $task->title }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-semibold
                                                                    @if($task->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                                    @elseif($task->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ __(ucfirst(str_replace('_', ' ', $task->status))) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-sm">
                                            {{ __(ucfirst($task->priority)) }}
                                        </td>
                                        <td class="py-3 px-4 text-sm">
                                            {{ $task->assignedUser?->name ?? __('Unassigned') }}
                                        </td>
                                        <td class="py-3 px-4 text-sm">
                                            {{ $task->end_date?->format('d.m.Y') ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="inline-flex space-x-3">
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                                    onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>