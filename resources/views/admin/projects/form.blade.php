@extends('layouts.admin')

@section('content')
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

{{-- Alpine components --}}
<script>
    window.tagInput = function(initial) {
        return {
            items: Array.isArray(initial) ? [...initial] : [],
            addItem(input) {
                const val = input.value.trim();
                if (val && !this.items.includes(val)) {
                    this.items.push(val);
                }
                input.value = '';
            }
        };
    };
</script>
<h1 class="text-2xl font-bold mb-6">{{ isset($project) ? 'Edit Project' : 'New Project' }}</h1>

@if($errors->any())
<div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-300 px-5 py-3 rounded-xl">
    <ul class="list-disc list-inside text-sm space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form id="project-form" action="{{ isset($project) ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
    method="POST" enctype="multipart/form-data" class="max-w-4xl space-y-6">
    @csrf
    @if(isset($project)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left Column --}}
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                    <select name="category_id" class="w-full bg-gray-900 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
                        <option value="" class="bg-gray-900">Select a category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $project->category_id ?? '') == $cat->id ? 'selected' : '' }} class="bg-gray-900">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Published Date</label>
                    <div class="relative">
                        <input type="text" name="published_at" id="published_at" readonly
                            value="{{ old('published_at', isset($project) && $project->published_at ? $project->published_at->format('Y-m-d') : '') }}"
                            placeholder="Select date..."
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none cursor-pointer">
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

            </div>
            <div class="flex items-center gap-3 p-4 bg-white/5 border border-white/10 rounded-xl">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                    {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }}
                    class="w-5 h-5 rounded accent-purple-500 cursor-pointer">
                <div>
                    <label for="is_featured" class="text-sm font-medium text-white cursor-pointer">Featured Project</label>
                    <p class="text-xs text-gray-500 mt-0.5">Show this project in the home page featured section</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Hero Image</label>
                @if(isset($project) && $project->hero_image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $project->hero_image) }}" alt="Current hero" class="h-24 rounded-lg border border-white/10 object-cover">
                </div>
                @endif
                <input type="file" name="hero_image" accept="image/*"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none file:bg-purple-600 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-1 file:mr-4">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Hero Video URL</label>
                <input type="url" name="hero_video" value="{{ old('hero_video', $project->hero_video ?? '') }}" placeholder="https://www.youtube.com/watch?v=..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Project Link</label>
                <input type="url" name="url" value="{{ old('url', $project->url ?? '') }}" placeholder="https://example.com or Play Store link"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
                <p class="text-xs text-gray-500 mt-1">Website URL or app store link (shown as a Visit button on the project page)</p>
            </div>
            <div x-data="tagInput({{ json_encode(old('software_used', isset($project) && $project->software_used ? $project->software_used : [])) }})">
                <label class="block text-sm font-medium text-gray-300 mb-2">Software Used</label>
                <input type="hidden" name="software_used" :value="JSON.stringify(items)">
                <div class="flex flex-wrap gap-2 p-3 bg-white/5 border border-white/10 rounded-xl min-h-[48px]">
                    <template x-for="(item, index) in items" :key="index">
                        <span class="inline-flex items-center gap-1.5 bg-purple-600/30 text-purple-300 text-sm px-3 py-1 rounded-lg border border-purple-500/20">
                            <span x-text="item"></span>
                            <button type="button" @click="items.splice(index, 1)" class="hover:text-white transition-colors">&times;</button>
                        </span>
                    </template>
                    <input type="text" x-ref="input" placeholder="Type & press Enter..."
                        @keydown.enter.prevent="addItem($refs.input)"
                        @keydown.comma.prevent="addItem($refs.input)"
                        class="flex-1 min-w-[120px] bg-transparent border-0 text-white text-sm px-1 py-1 focus:outline-none placeholder-gray-500">
                </div>
                <p class="text-xs text-gray-500 mt-1">Press Enter or comma to add</p>
            </div>
            <div x-data="tagInput({{ json_encode(old('process_steps', isset($project) && $project->process_steps ? $project->process_steps : [])) }})">
                <label class="block text-sm font-medium text-gray-300 mb-2">Process Steps</label>
                <input type="hidden" name="process_steps" :value="JSON.stringify(items)">
                <div class="space-y-2">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-2 group">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-purple-600/30 text-purple-300 text-xs flex items-center justify-center border border-purple-500/20" x-text="index + 1"></span>
                            <input type="text" :value="item" @input="items[index] = $event.target.value"
                                class="flex-1 bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white text-sm focus:border-purple-500 focus:outline-none">
                            <button type="button" @click="items.splice(index, 1)"
                                class="w-7 h-7 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-300 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-all">&times;</button>
                        </div>
                    </template>
                    <button type="button" @click="items.push('')"
                        class="flex items-center gap-2 text-sm text-purple-400 hover:text-purple-300 transition-colors mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add step
                    </button>
                </div>
            </div>
        </div>

        {{-- Right Column: Gallery --}}
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Gallery Images</label>

                {{-- Existing images --}}
                <div class="grid grid-cols-3 gap-3 mb-4" id="existing-gallery">
                    @if(isset($project) && $project->gallery && count($project->gallery) > 0)
                        @foreach($project->gallery as $index => $img)
                        <div class="relative group" data-gallery-item data-path="{{ $img }}">
                            <img src="{{ asset('storage/' . $img) }}" alt="Gallery image" class="w-full h-28 object-cover rounded-lg border border-white/10">
                            <button type="button" onclick="galleryRemoveExisting(this)"
                                class="absolute top-1 right-1 w-6 h-6 bg-red-600/90 hover:bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-all shadow-lg">&times;</button>
                        </div>
                        @endforeach
                    @endif
                </div>

                {{-- New image previews --}}
                <div class="grid grid-cols-3 gap-3 mb-4 hidden" id="new-gallery-previews"></div>

                {{-- Drop zone --}}
                <div id="gallery-dropzone"
                    class="relative border-2 border-dashed border-white/10 hover:border-white/20 rounded-xl p-8 text-center cursor-pointer transition-all group">
                    <input type="file" name="gallery[]" accept="image/*" multiple id="gallery-file-input" class="hidden">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-purple-500/10 flex items-center justify-center group-hover:bg-purple-500/20 transition-colors">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-300">Drop images here or <span class="text-purple-400 font-medium">browse</span></p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP up to 5MB each</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-gray-500" id="gallery-count">0 new image(s) selected</p>
                    <button type="button" id="gallery-clear-btn" onclick="galleryClearAll()" class="text-xs text-red-400 hover:text-red-300 transition-colors hidden">Clear all new</button>
                </div>

                <input type="hidden" name="removed_gallery" id="removed_gallery" value="[]">
            </div>
        </div>
    </div>

    {{-- Full-width Description with TinyMCE --}}
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Description (Rich Editor)</label>
        <textarea name="description" id="description-editor" rows="12"
            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('description', $project->description ?? '') }}</textarea>
    </div>

    @if(isset($project))
    <div>
        <a href="{{ route('admin.projects.download-images', $project) }}"
            class="inline-flex items-center gap-2 bg-green-600/20 hover:bg-green-600/30 text-green-400 border border-green-500/20 font-medium px-5 py-2.5 rounded-xl transition-all text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download All Images (ZIP)
        </a>
        <p class="text-xs text-gray-500 mt-1">Downloads hero image + all gallery images as a ZIP file</p>
    </div>
    @endif

    <div class="flex gap-3">
        <button type="submit"
            class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">{{
            isset($project) ? 'Update' : 'Create' }} Project</button>
        <a href="{{ route('admin.projects.index') }}"
            class="bg-white/5 hover:bg-white/10 text-gray-300 font-medium px-6 py-3 rounded-xl transition-all">Cancel</a>
    </div>
</form>

{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- TinyMCE CDN --}}
<script src="https://cdn.tiny.cloud/1/2ybotr2gj2jba7rs525xlvymht3kg2qv4833vglziifs7kj8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

{{-- Submit handler — must be separate to never be killed by init errors --}}
<script>
    document.getElementById('project-form').addEventListener('submit', function(e) {
        try {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
            document.querySelectorAll('input[name="software_used"], input[name="process_steps"]').forEach(function(input) {
                if (!input.value || input.value === '') {
                    input.value = '[]';
                }
            });
        } catch (err) {
            console.error('Form submit handler error:', err);
        }
    });
</script>

{{-- TinyMCE init --}}
<script>
    try {
        tinymce.init({
            selector: '#description-editor',
            skin: 'oxide-dark',
            content_css: 'dark',
            min_height: 600,
            max_height: 1200,
            autoresize_bottom_margin: 50,
            menubar: true,
            plugins: 'lists link image code table media autolink hr paste autoresize',
            toolbar: 'undo redo | styles fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link image media hr | table | code',
            font_size_formats: '10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 42px 48px 60px 72px',
            content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; color: #e5e7eb; background-color: #111827; } img { max-width: 100%; height: auto; } video { max-width: 100%; height: auto; } h1 { font-size: 2.5em; font-weight: 700; line-height: 1.2; margin: 0.5em 0; } h2 { font-size: 2em; font-weight: 700; line-height: 1.25; margin: 0.5em 0; } h3 { font-size: 1.6em; font-weight: 600; line-height: 1.3; margin: 0.5em 0; } h4 { font-size: 1.3em; font-weight: 600; margin: 0.5em 0; } h5 { font-size: 1.1em; font-weight: 600; margin: 0.5em 0; } h6 { font-size: 0.95em; font-weight: 600; margin: 0.5em 0; } p { margin: 0.4em 0; }',
            branding: false,
            images_upload_url: '{{ route("admin.projects.upload-image") }}',
            images_upload_credentials: true,
            automatic_uploads: true,
            file_picker_types: 'image media',
            media_live_embeds: true,
            paste_data_images: true,
            contextmenu: 'link image embedimage inserttable | cell row column deletetable',
            setup: function(editor) {
                // Right-click "Embed Image" menu item
                editor.ui.registry.addMenuItem('embedimage', {
                    text: 'Embed Image',
                    icon: 'image',
                    onAction: function() {
                        var input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*';
                        input.onchange = function() {
                            var file = input.files[0];
                            if (!file) return;
                            var formData = new FormData();
                            formData.append('file', file, file.name);
                            formData.append('_token', document.querySelector('input[name="_token"]').value);
                            fetch('{{ route("admin.projects.upload-image") }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                    'Accept': 'application/json',
                                }
                            })
                            .then(function(resp) { return resp.json(); })
                            .then(function(result) {
                                editor.insertContent('<img src="' + result.location + '" alt="" style="max-width:100%;height:auto;" />');
                            })
                            .catch(function() { alert('Image upload failed'); });
                        };
                        input.click();
                    }
                });
            },
            images_upload_handler: function(blobInfo, success, failure) {
                var formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                fetch('{{ route("admin.projects.upload-image") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    }
                })
                .then(function(resp) { return resp.json(); })
                .then(function(result) { success(result.location); })
                .catch(function() { failure('Image upload failed'); });
            },
        });
    } catch (err) {
        console.error('TinyMCE init error:', err);
    }
</script>

{{-- Flatpickr init --}}
<script>
    try {
        flatpickr('#published_at', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'F j, Y',
            allowInput: false,
            theme: 'dark',
        });
    } catch (err) {
        console.error('Flatpickr init error:', err);
    }
</script>

{{-- Gallery Uploader --}}
<script>
    (function() {
        const dropzone = document.getElementById('gallery-dropzone');
        const fileInput = document.getElementById('gallery-file-input');
        const previewsContainer = document.getElementById('new-gallery-previews');
        const countEl = document.getElementById('gallery-count');
        const clearBtn = document.getElementById('gallery-clear-btn');
        let files = [];

        // Click to browse
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });

        // File input change
        fileInput.addEventListener('change', function() {
            addFiles(this.files);
        });

        // Drag events
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.classList.add('border-purple-500', 'bg-purple-500/10');
            dropzone.classList.remove('border-white/10');
        });
        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropzone.classList.remove('border-purple-500', 'bg-purple-500/10');
            dropzone.classList.add('border-white/10');
        });
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.classList.remove('border-purple-500', 'bg-purple-500/10');
            dropzone.classList.add('border-white/10');
            addFiles(e.dataTransfer.files);
        });

        function addFiles(newFiles) {
            for (const file of newFiles) {
                if (!file.type.startsWith('image/')) continue;
                if (file.size > 5 * 1024 * 1024) continue;
                files.push(file);
            }
            renderPreviews();
            syncInput();
        }

        function renderPreviews() {
            previewsContainer.innerHTML = '';
            if (files.length === 0) {
                previewsContainer.classList.add('hidden');
                clearBtn.classList.add('hidden');
            } else {
                previewsContainer.classList.remove('hidden');
                clearBtn.classList.remove('hidden');
            }
            countEl.textContent = files.length + ' new image(s) selected';

            files.forEach(function(file, index) {
                const url = URL.createObjectURL(file);
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML =
                    '<img src="' + url + '" alt="New" class="w-full h-28 object-cover rounded-lg border border-purple-500/30">' +
                    '<button type="button" class="absolute top-1 right-1 w-6 h-6 bg-red-600/90 hover:bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-all shadow-lg">&times;</button>' +
                    '<div class="absolute bottom-1 left-1 right-1 text-[10px] text-white/70 truncate px-1">' + file.name.replace(/</g, '&lt;') + '</div>';
                div.querySelector('button').addEventListener('click', function() {
                    URL.revokeObjectURL(url);
                    files.splice(index, 1);
                    renderPreviews();
                    syncInput();
                });
                previewsContainer.appendChild(div);
            });
        }

        function syncInput() {
            const dt = new DataTransfer();
            files.forEach(function(f) { dt.items.add(f); });
            fileInput.files = dt.files;
        }

        // Clear all
        window.galleryClearAll = function() {
            files = [];
            renderPreviews();
            syncInput();
        };

        // Remove existing image
        window.galleryRemoveExisting = function(btn) {
            const item = btn.closest('[data-gallery-item]');
            const path = item.getAttribute('data-path');
            const hidden = document.getElementById('removed_gallery');
            const removed = JSON.parse(hidden.value);
            removed.push(path);
            hidden.value = JSON.stringify(removed);
            item.remove();
        };
    })();
</script>
@endsection