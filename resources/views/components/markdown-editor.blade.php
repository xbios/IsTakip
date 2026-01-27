@props(['name', 'value' => '', 'label' => 'Content'])

<div x-data="{ 
    content: '{{ addslashes($value) }}',
    preview: '',
    loading: false,
    tab: 'edit',
    updatePreview() {
        this.loading = true;
        fetch('{{ route('markdown.preview') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ content: this.content })
        })
        .then(response => response.json())
        .then(data => {
            this.preview = data.html;
            this.loading = false;
        });
    }
}" class="w-full">
    <div class="flex items-center justify-between mb-2">
        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ $label }}</label>
        <div class="flex space-x-2 bg-gray-100 dark:bg-gray-800 p-1 rounded-md">
            <button type="button" @click="tab = 'edit'"
                :class="tab === 'edit' ? 'bg-white dark:bg-gray-700 shadow-sm' : ''"
                class="px-3 py-1 text-xs rounded-md transition-all">Edit</button>
            <button type="button" @click="tab = 'preview'; updatePreview()"
                :class="tab === 'preview' ? 'bg-white dark:bg-gray-700 shadow-sm' : ''"
                class="px-3 py-1 text-xs rounded-md transition-all">Preview</button>
        </div>
    </div>

    <div x-show="tab === 'edit'" class="relative">
        <textarea name="{{ $name }}" x-model="content"
            class="w-full min-h-[300px] border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm font-mono text-sm"
            placeholder="Write markdown here..."></textarea>
    </div>

    <div x-show="tab === 'preview'"
        class="w-full min-h-[300px] p-4 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md overflow-auto prose dark:prose-invert max-w-none">
        <div x-show="loading" class="flex items-center justify-center h-full">
            <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>
        <div x-html="preview" x-show="!loading"></div>
    </div>
</div>