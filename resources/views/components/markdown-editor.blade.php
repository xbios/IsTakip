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
        
        this.$nextTick(() => {
            textarea.focus();
            const newCursorPos = start + text.length;
            textarea.setSelectionRange(newCursorPos, newCursorPos);
        });
    },
    wrapSelection(before, after) {
        const textarea = this.$refs.textarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = this.content.substring(start, end);
        const wrappedText = before + selectedText + after;
        
        this.content = this.content.substring(0, start) + wrappedText + this.content.substring(end);
        
        this.$nextTick(() => {
            textarea.focus();
            textarea.setSelectionRange(start + before.length, end + before.length);
        });
    },
    insertLineStart(prefix) {
        const textarea = this.$refs.textarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        
        // Satırın başlangıcını bul
        let lineStart = this.content.lastIndexOf('\n', start - 1) + 1;
        this.content = this.content.substring(0, lineStart) + prefix + ' ' + this.content.substring(lineStart);
        
        this.$nextTick(() => {
            textarea.focus();
            const newPos = start + prefix.length + 1;
            textarea.setSelectionRange(newPos, newPos);
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

    <div x-show="tab === 'edit'"
        class="border border-gray-300 dark:border-gray-700 rounded-md overflow-hidden bg-white dark:bg-gray-900">
        <!-- Toolbar -->
        <div class="flex flex-wrap items-center gap-1 p-2 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <!-- Bold -->
            <button type="button" @click="wrapSelection('**', '**')"
                class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded transition-colors text-gray-600 dark:text-gray-300"
                title="{{ __('Bold') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6zM6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"></path>
                </svg>
            </button>
            <!-- Italic -->
            <button type="button" @click="wrapSelection('*', '*')"
                class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded transition-colors text-gray-600 dark:text-gray-300"
                title="{{ __('Italic') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 20l4-16m4 4l4 4m-4-4l-4-4"></path>
                </svg>
            </button>
            <div class="w-px h-4 bg-gray-300 dark:border-gray-600 mx-1"></div>
            <!-- Heading 1 -->
            <button type="button" @click="insertLineStart('#')"
                class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded transition-colors text-gray-600 dark:text-gray-300 text-xs font-bold"
                title="{{ __('Heading 1') }}">H1</button>
            <!-- Heading 2 -->
            <button type="button" @click="insertLineStart('##')"
                class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded transition-colors text-gray-600 dark:text-gray-300 text-xs font-bold"
                title="{{ __('Heading 2') }}">H2</button>
            <div class="w-px h-4 bg-gray-300 dark:border-gray-600 mx-1"></div>
            <!-- List -->
            <button type="button" @click="insertLineStart('-')"
                class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded transition-colors text-gray-600 dark:text-gray-300"
                title="{{ __('List') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <!-- Code Block -->
            <button type="button" @click="wrapSelection('```\n', '\n```')"
                class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded transition-colors text-gray-600 dark:text-gray-300"
                title="{{ __('Code Block') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 20l4-16m4 4l4 4m-4-4l-4-4M8 6h13M8 12h13M8 18h13"></path>
                </svg>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </button>
            <div class="flex-1"></div>
            <!-- Image Upload -->
            <input type="file" x-ref="imageInput" @change="handleImageUpload" class="hidden" accept="image/*">
            <button type="button" @click="$refs.imageInput.click()"
                class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded transition-colors text-gray-600 dark:text-gray-300"
                title="{{ __('Upload Image') }}">
                <svg x-show="!uploading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            class="w-full min-h-[300px] border-0 dark:bg-gray-900 dark:text-gray-300 focus:ring-0 rounded-none font-mono text-sm resize-none"
            placeholder="Write markdown here..."></textarea>
    </div>

    <div x-show="tab === 'preview'"
        class="w-full min-h-[340px] p-4 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md overflow-auto prose dark:prose-invert max-w-none">
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