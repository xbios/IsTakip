<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-yellow-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Pending Tasks') }}</div>
                    <div class="text-2xl font-bold dark:text-gray-100">{{ $stats['pending_tasks'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('In Progress') }}</div>
                    <div class="text-2xl font-bold dark:text-gray-100">{{ $stats['in_progress_tasks'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Completed') }}</div>
                    <div class="text-2xl font-bold dark:text-gray-100">{{ $stats['completed_tasks'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-purple-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Documents') }}</div>
                    <div class="text-2xl font-bold dark:text-gray-100">{{ $stats['total_documents'] }}</div>
                </div>
            </div>

            <!-- Recent Tasks -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">{{ __('Recent Tasks') }}
                    </h3>

                    <div class="divide-y dark:divide-gray-700">
                        @forelse($recent_tasks as $task)
                            <div class="py-4 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('tasks.show', $task) }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                        {{ $task->title }}
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $task->assignedUser?->name ?? __('Unassigned') }} •
                                        {{ $task->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            @if($task->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($task->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                    {{ str_replace('_', ' ', $task->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="py-4 text-gray-500 dark:text-gray-400 text-sm italic">
                                {{ __('No recent tasks found.') }}</p>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('tasks.index') }}"
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                            {{ __('View all tasks') }} &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>