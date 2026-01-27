<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $document->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('documents.edit', $document) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8 pb-4 border-b dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <span
                                class="px-2 py-1 rounded text-xs font-bold uppercase tracking-widest {{ $document->related_type === 'global' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' }}">
                                {{ $document->related_type }}
                            </span>
                            @if($document->task)
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Related to Task: <a href="{{ route('tasks.show', $document->task) }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $document->task->title }}</a>
                                </span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 text-right">
                            <p>Created by <strong>{{ $document->creator->name }}</strong></p>
                            <p>{{ $document->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="prose dark:prose-invert max-w-none">
                        {!! $content_html !!}
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('documents.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                    &larr; Back to List
                </a>
            </div>
        </div>
    </div>
</x-app-layout>