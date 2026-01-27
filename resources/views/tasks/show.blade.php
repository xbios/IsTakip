<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $task->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('tasks.edit', $task) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Task Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                        <div class="flex justify-between mb-4">
                            <div>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($task->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($task->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                    {{ __(ucfirst(str_replace('_', ' ', $task->status))) }}
                                </span>
                                <span
                                    class="ml-2 px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ __('Priority') }}: {{ __(ucfirst($task->priority)) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Created by :name on :date', ['name' => $task->creator->name, 'date' => $task->created_at->format('d.m.Y')]) }}
                            </div>
                        </div>

                        <div class="prose dark:prose-invert max-w-none mb-6">
                            {!! $description_html !!}
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div>
                                <span
                                    class="font-bold block uppercase tracking-wider text-[10px] text-gray-500 dark:text-gray-400">Assigned
                                    To') }}</span>
                                {{ $task->assignedUser?->name ?? 'Unassigned' }}
                            </div>
                            <div>
                                <span
                                    class="font-bold block uppercase tracking-wider text-[10px] text-gray-500 dark:text-gray-400">{{ __('Timeline') }}</span>
                                {{ $task->start_date?->format('d.m.Y') ?? '...' }} -
                                {{ $task->end_date?->format('d.m.Y') ?? '...' }}
                            </div>
                        </div>
                    </div>

                    <!-- Task Steps -->
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Sub-tasks / Steps') }}</h3>

                        <div class="space-y-2 mb-6" x-data="{}">
                            @foreach($task->steps as $step)
                                <div
                                    class="flex items-center justify-between p-3 border dark:border-gray-700 rounded-lg group">
                                    <form action="{{ route('steps.update', $step) }}" method="POST"
                                        class="flex items-center flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox" name="is_completed" onchange="this.form.submit()" {{ $step->is_completed ? 'checked' : '' }}
                                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                        <span
                                            class="ml-3 {{ $step->is_completed ? 'line-through text-gray-400' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $step->title }}
                                        </span>
                                    </form>
                                    <form action="{{ route('steps.destroy', $step) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Delete step?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-400 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <form action="{{ route('tasks.steps.store', $task) }}" method="POST" class="flex mt-4">
                            @csrf
                            <x-text-input name="title" :placeholder="__('Add a new step...')" class="flex-1 mr-2"
                                required />
                            <x-primary-button>{{ __('Add') }}</x-primary-button>
                        </form>
                    </div>
                </div>

                <!-- Activity Logs -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-4">
                            {{ __('Activity Log') }}
                        </h3>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($task->logs as $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span
                                                    class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <span
                                                            class="text-[10px] font-bold">{{ strtoupper(substr($log->user->name, 0, 1)) }}</span>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            <span
                                                                class="font-medium text-gray-900 dark:text-gray-100">{{ $log->user->name }}</span>
                                                            {{ $log->description }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="whitespace-nowrap text-right text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $log->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>