@props(['name', 'value' => '', 'label' => 'Content'])

<div x-data="{ 
    content: @js($value),
    preview: '',
    loading: false,
    tab: 'edit',
    uploading: false,
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
    },
    handleImageUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        this.uploading = true;
        const formData = new FormData();
        formData.append('image', file);

        fetch('{{ route('media.upload') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.url) {
                const imageMarkdown = `![${data.name}](${data.url})`;
                this.insertText(imageMarkdown);
            } else {
                alert('Yükleme hatası: ' + (data.error || 'Bilinmeyen hata'));
            }
            this.uploading = false;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Yükleme sırasında bir hata oluştu.');
            this.uploading = false;
        });
    },
    insertText(text) {
        const textarea = this.$refs.textarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        this.content = this.content.substring(0, start) + text + this.content.substring(end);
        
        // Yeniden odaklan ve imleci sona al
        this.$nextTick(() => {
            textarea.focus();
            const newCursorPos = start + text.length;
            textarea.setSelectionRange(newCursorPos, newCursorPos);
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
        <div class="absolute right-2 top-2 z-10">
            <input type="file" x-ref="imageInput" @change="handleImageUpload" class="hidden" accept="image/*">
            <button type="button" @click="$refs.imageInput.click()"
                class="p-1.5 bg-white dark:bg-gray-700 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                title="Resim Yükle">
                <svg x-show="!uploading" class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <svg x-show="uploading" class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </button>
        </div>
        <textarea name="{{ $name }}" x-model="content" x-ref="textarea"
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