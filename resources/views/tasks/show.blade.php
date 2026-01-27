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
                            {{ __('Sub-tasks / Steps') }}
                        </h3>

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

                <!-- Activity Logs and Related Documents -->
                <div class="space-y-6" x-data="{ 
                    showModal: false, 
                    modalTitle: '', 
                    modalContent: '', 
                    loading: false,
                    openDocument(id) {
                        this.showModal = true;
                        this.loading = true;
                        this.modalTitle = '...';
                        this.modalContent = '';
                        
                        fetch(`/documents/${id}/content`)
                            .then(response => response.json())
                            .then(data => {
                                this.modalTitle = data.title;
                                this.modalContent = data.html;
                                this.loading = false;
                            });
                    }
                }">
                    <!-- Activity Logs -->
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

                    <!-- Related Documents -->
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-4">
                            {{ __('Related Documents') }}
                        </h3>
                        @if($task->documents->count() > 0)
                            <div class="space-y-3">
                                @foreach($task->documents as $doc)
                                    <button type="button" @click="openDocument({{ $doc->id }})"
                                        class="w-full flex items-center p-3 border dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group text-left">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 mr-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $doc->title }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $doc->creator->name }} • {{ $doc->created_at->format('d.m.Y') }}
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No related documents found.') }}</p>
                        @endif
                    </div>

                    <!-- Modal -->
                    <template x-teleport="body">
                        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div
                                class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80"
                                    @click="showModal = false"></div>

                                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg">

                                    <div
                                        class="flex items-center justify-between mb-4 border-b dark:border-gray-700 pb-3">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100"
                                            x-text="modalTitle"></h3>
                                        <button @click="showModal = false"
                                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="relative min-h-[400px]">
                                        <div x-show="loading"
                                            class="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-800 bg-opacity-50">
                                            <svg class="w-10 h-10 text-indigo-500 animate-spin"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="prose dark:prose-invert max-w-none" x-html="modalContent"></div>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <button type="button" @click="showModal = false"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                                            {{ __('Close') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>