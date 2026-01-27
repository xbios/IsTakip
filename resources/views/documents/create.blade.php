<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('documents.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Title -->
                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                    :value="old('title')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Related Type -->
                                <div>
                                    <x-input-label for="related_type" :value="__('Document Type')" />
                                    <select id="related_type" name="related_type"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="global" {{ old('related_type') == 'global' ? 'selected' : '' }}>
                                            Global (General)</option>
                                        <option value="task" {{ old('related_type') == 'task' ? 'selected' : '' }}>Task
                                            Specific</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('related_type')" />
                                </div>

                                <!-- Related Task -->
                                <div x-data="{ show: {{ old('related_type') == 'task' ? 'true' : 'false' }} }"
                                    x-init="$watch('related_type', value => show = value === 'task')">
                                    <div x-show="show">
                                        <x-input-label for="related_id" :value="__('Related Task')" />
                                        <select id="related_id" name="related_id"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Select a task</option>
                                            @foreach($tasks as $task)
                                                <option value="{{ $task->id }}" {{ old('related_id') == $task->id ? 'selected' : '' }}>
                                                    {{ $task->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('related_id')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Content (Markdown Editor) -->
                            <div>
                                <x-markdown-editor name="content_md" :value="old('content_md')"
                                    label="Document Content (Markdown)" />
                                <x-input-error class="mt-2" :messages="$errors->get('content_md')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Create Document') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>