<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Attachments') }}
            </h2>
            <a href="{{ route('attachments.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Upload File') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-8 flex items-center space-x-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Filtrele') }}:</span>
                        <div class="flex items-center space-x-4">
                            @foreach(['hepsi' => 'Hepsi', 'genel' => 'Genel', 'gorev' => 'Görev'] as $value => $label)
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="filter" value="{{ $value }}" 
                                        class="hidden peer"
                                        {{ request('filter', 'hepsi') === $value ? 'checked' : '' }}
                                        onchange="window.location.href = '{{ route('attachments.index') }}?filter=' + this.value">
                                    <div class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-200 
                                        peer-checked:bg-indigo-600 peer-checked:text-white 
                                        bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600
                                        hover:border-indigo-400 dark:hover:border-indigo-500 shadow-sm">
                                        {{ __($label) }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @if($attachments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b dark:border-gray-700">
                                        <th class="py-3 px-4 uppercase font-semibold text-sm">{{ __('Title') }}</th>
                                        <th class="py-3 px-4 uppercase font-semibold text-sm">{{ __('Related Task') }}</th>
                                        <th class="py-3 px-4 uppercase font-semibold text-sm">{{ __('File Type') }}</th>
                                        <th class="py-3 px-4 uppercase font-semibold text-sm">{{ __('Size') }}</th>
                                        <th class="py-3 px-4 uppercase font-semibold text-sm text-right">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attachments as $attachment)
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                            <td class="py-3 px-4 font-medium">{{ $attachment->title }}</td>
                                            <td class="py-3 px-4 text-sm">
                                                @if($attachment->task)
                                                    <a href="{{ route('tasks.show', $attachment->task) }}" class="text-blue-500 hover:underline">
                                                        {{ $attachment->task->title }}
                                                    </a>
                                                @else
                                                    <span class="text-gray-500 italic">{{ __('Global') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $attachment->file_type }}
                                            </td>
                                            <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($attachment->file_size / 1024 / 1024, 2) }} MB
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('attachments.show', $attachment) }}" target="_blank"
                                                        class="text-blue-500 hover:text-blue-700 p-1" title="{{ __('View') }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.text-gray-52 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('attachments.destroy', $attachment) }}" method="POST"
                                                        onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="{{ __('Delete') }}">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
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
                            {{ $attachments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No files found.') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Get started by uploading a new file.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
