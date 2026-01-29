<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Documents') }}
            </h2>
            <a href="{{ route('documents.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Create Document') }}
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

                    <div class="mb-8 flex items-center space-x-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Filtrele') }}:</span>
                        <div class="flex items-center space-x-4">
                            @foreach(['hepsi' => 'Hepsi', 'genel' => 'Genel', 'gorev' => 'Görev'] as $value => $label)
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="filter" value="{{ $value }}" 
                                        class="hidden peer"
                                        {{ request('filter', 'hepsi') === $value ? 'checked' : '' }}
                                        onchange="window.location.href = '{{ route('documents.index') }}?filter=' + this.value">
                                    <div class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                                        peer-checked:bg-indigo-600 peer-checked:text-white 
                                        bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600
                                        hover:border-indigo-400 dark:hover:border-indigo-500 shadow-sm">
                                        {{ __($label) }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($documents as $document)
                            <div
                                class="border dark:border-gray-700 rounded-lg p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2">
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest {{ $document->related_type === 'global' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' }}">
                                        {{ $document->related_type }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold mb-2 pr-12">
                                    <a href="{{ route('documents.show', $document) }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $document->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-3 italic">
                                    {{ Str::limit(strip_tags($document->content_md), 100) }}
                                </p>
                                <div class="flex justify-between items-center text-xs text-gray-400">
                                    <span>By {{ $document->creator->name }}</span>
                                    <span>{{ $document->created_at->format('d.m.Y') }}</span>
                                </div>

                                <div class="mt-4 flex space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('documents.edit', $document) }}"
                                        class="text-blue-500 hover:text-blue-700">Düzenle</a>
                                    <form action="{{ route('documents.destroy', $document) }}" method="POST"
                                        onsubmit="return confirm('Delete document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Sil</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>