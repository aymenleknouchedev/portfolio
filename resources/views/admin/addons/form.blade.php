@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">{{ isset($addon) ? 'Edit Add-on' : 'New Add-on' }}</h1>

<form action="{{ isset($addon) ? route('admin.addons.update', $addon) : route('admin.addons.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6">
    @csrf
    @if(isset($addon)) @method('PUT') @endif

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
        <select name="category_id" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $addon->category_id ?? '') == $cat->id ? 'selected' : '' }} class="bg-gray-900">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
        <input type="text" name="name" value="{{ old('name', $addon->name ?? '') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
        <textarea name="description" id="addon-description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('description', $addon->description ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Price ($)</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $addon->price ?? '') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">YouTube Video URL</label>
            <input type="text" name="demo_video_url" value="{{ old('demo_video_url', $addon->demo_video_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Cover Image</label>
        <input type="file" name="cover_image" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none file:bg-purple-600 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-1 file:mr-4 file:cursor-pointer">
        @if(isset($addon) && $addon->cover_image)
            <div class="mt-2 flex items-center gap-3">
                <img src="{{ asset('storage/' . $addon->cover_image) }}" alt="Cover" class="h-20 rounded-lg object-cover">
                <span class="text-sm text-gray-500">Current cover image</span>
            </div>
        @endif
    </div>
    {{-- Screenshots / Album --}}
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Screenshots / Album</label>

        {{-- Existing screenshots --}}
        <div class="grid grid-cols-3 gap-3 mb-4" id="existing-screenshots">
            @if(isset($addon) && $addon->screenshots && count($addon->screenshots) > 0)
                @foreach($addon->screenshots as $index => $screenshot)
                <div class="relative group" data-screenshot-item data-path="{{ $screenshot }}">
                    <img src="{{ asset('storage/' . $screenshot) }}" alt="Screenshot" class="w-full h-28 object-cover rounded-lg border border-white/10">
                    <button type="button" onclick="screenshotRemoveExisting(this)"
                        class="absolute top-1 right-1 w-6 h-6 bg-red-600/90 hover:bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-all shadow-lg">&times;</button>
                </div>
                @endforeach
            @endif
        </div>

        {{-- New image previews --}}
        <div class="grid grid-cols-3 gap-3 mb-4 hidden" id="new-screenshots-previews"></div>

        {{-- Drop zone --}}
        <div id="screenshots-dropzone"
            class="relative border-2 border-dashed border-white/10 hover:border-white/20 rounded-xl p-8 text-center cursor-pointer transition-all group">
            <input type="file" name="screenshots[]" accept="image/*" multiple id="screenshots-file-input" class="hidden">
            <div class="flex flex-col items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-purple-500/10 flex items-center justify-center group-hover:bg-purple-500/20 transition-colors">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-300">Drop screenshots here or <span class="text-purple-400 font-medium">browse</span></p>
                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP up to 5MB each</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-2">
            <p class="text-xs text-gray-500" id="screenshots-count">0 new image(s) selected</p>
            <button type="button" id="screenshots-clear-btn" onclick="screenshotsClearAll()" class="text-xs text-red-400 hover:text-red-300 transition-colors hidden">Clear all new</button>
        </div>

        <input type="hidden" name="removed_screenshots" id="removed_screenshots" value="[]">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Features (JSON array)</label>
        <textarea name="features" rows="3" placeholder='["Feature 1", "Feature 2"]' class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none font-mono text-sm">{{ old('features', isset($addon) ? json_encode($addon->features) : '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Downloadable File</label>
        <input type="file" name="file" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none file:bg-purple-600 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-1 file:mr-4 file:cursor-pointer">
        @if(isset($addon) && $addon->file_path && !str_starts_with($addon->file_path, 'http'))
            <p class="text-sm text-gray-500 mt-1">Current: {{ basename($addon->file_path) }}</p>
        @endif
        <div class="mt-3">
            <label class="block text-xs font-medium text-gray-400 mb-1">— or paste an external download URL (Google Drive, Dropbox, GitHub, etc.)</label>
            <input type="url" name="download_url"
                value="{{ old('download_url', (isset($addon) && $addon->file_path && str_starts_with($addon->file_path, 'http')) ? $addon->file_path : '') }}"
                placeholder="https://drive.google.com/uc?export=download&id=..."
                class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white text-sm focus:border-purple-500 focus:outline-none">
            <p class="text-xs text-gray-600 mt-1">If both a file and a URL are provided, the uploaded file takes priority.</p>
        </div>
    </div>
    <div>
        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $addon->is_featured ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded bg-white/5 border-white/10 text-purple-600 focus:ring-purple-500">
            <span class="text-sm text-gray-300">Featured</span>
        </label>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">{{ isset($addon) ? 'Update' : 'Create' }} Add-on</button>
        <a href="{{ route('admin.addons.index') }}" class="bg-white/5 hover:bg-white/10 text-gray-300 font-medium px-6 py-3 rounded-xl transition-all">Cancel</a>
    </div>
</form>

{{-- Screenshots Uploader --}}
<script>
(function() {
    const dropzone = document.getElementById('screenshots-dropzone');
    const fileInput = document.getElementById('screenshots-file-input');
    const previewsContainer = document.getElementById('new-screenshots-previews');
    const countEl = document.getElementById('screenshots-count');
    const clearBtn = document.getElementById('screenshots-clear-btn');
    let files = [];

    dropzone.addEventListener('click', function() { fileInput.click(); });

    fileInput.addEventListener('change', function() { addFiles(this.files); });

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

    window.screenshotsClearAll = function() {
        files = [];
        renderPreviews();
        syncInput();
    };

    window.screenshotRemoveExisting = function(btn) {
        const item = btn.closest('[data-screenshot-item]');
        const path = item.getAttribute('data-path');
        const hidden = document.getElementById('removed_screenshots');
        const removed = JSON.parse(hidden.value);
        removed.push(path);
        hidden.value = JSON.stringify(removed);
        item.remove();
    };
})();
</script>

<script src="https://cdn.tiny.cloud/1/2ybotr2gj2jba7rs525xlvymht3kg2qv4833vglziifs7kj8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#addon-description',
        skin: 'oxide-dark',
        content_css: 'dark',
        menubar: false,
        plugins: 'lists link image code table',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link image table | code',
        height: 300,
        branding: false,
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; color: #e5e7eb; background: #111827; }',
    });

    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            tinymce.triggerSave();
        });
    }
});
</script>
@endsection
