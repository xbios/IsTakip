<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
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
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Attachments') }}</div>
                    <div class="text-2xl font-bold dark:text-gray-100">{{ $stats['total_attachments'] }}</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <a href="{{ route('tasks.create') }}"
                    class="group bg-indigo-600 hover:bg-indigo-700 p-4 rounded-lg shadow flex items-center transition-colors">
                    <div
                        class="bg-indigo-500 group-hover:bg-indigo-600 p-3 rounded-md mr-4 transition-colors text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white font-bold">{{ __('New Task') }}</div>
                        <div class="text-indigo-100 text-xs">{{ __('Create a new task to track') }}</div>
                    </div>
                </a>
                <a href="{{ route('documents.create') }}"
                    class="group bg-purple-600 hover:bg-purple-700 p-4 rounded-lg shadow flex items-center transition-colors">
                    <div
                        class="bg-purple-500 group-hover:bg-purple-600 p-3 rounded-md mr-4 transition-colors text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white font-bold">{{ __('New Document') }}</div>
                        <div class="text-purple-100 text-xs">{{ __('Create a new markdown document') }}</div>
                    </div>
                </a>
                <a href="{{ route('attachments.create') }}"
                    class="group bg-blue-600 hover:bg-blue-700 p-4 rounded-lg shadow flex items-center transition-colors">
                    <div class="bg-blue-500 group-hover:bg-blue-600 p-3 rounded-md mr-4 transition-colors text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white font-bold">{{ __('Upload File') }}</div>
                        <div class="text-blue-100 text-xs">{{ __('Upload PDF, Word, Excel etc.') }}</div>
                    </div>
                </a>
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
                                {{ __('No recent tasks found.') }}
                            </p>
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