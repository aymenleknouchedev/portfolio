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
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                    <select name="category_id" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
                        <option value="">Select a category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $project->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
            height: 400,
            menubar: false,
            plugins: 'lists link image code table media',
            toolbar: 'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link image media | table | code',
            content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; color: #e5e7eb; background-color: #111827; }',
            branding: false,
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