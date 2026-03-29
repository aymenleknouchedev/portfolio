@extends('layouts.admin')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Hero Settings</h1>
            <p class="text-gray-400 text-sm mt-1">Manage the homepage hero section content</p>
        </div>
    </div>

    {{-- Settings Navigation --}}
    <div class="flex gap-2 mb-8 flex-wrap">
        <a href="{{ route('admin.settings.hero') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-purple-600 text-white">Hero</a>
        <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">General</a>
        <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Social Media</a>
        <a href="{{ route('admin.settings.about') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">About Section</a>
        <a href="{{ route('admin.settings.account') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Account</a>
    </div>

    <form action="{{ route('admin.settings.hero.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="hero-form">
        @csrf
        @method('PUT')

        {{-- Upload Progress --}}
        <div id="upload-progress" class="hidden rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-4">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Uploading...
            </h2>
            <div class="relative w-full h-3 bg-white/5 rounded-full overflow-hidden">
                <div id="progress-bar" class="absolute inset-y-0 left-0 bg-gradient-to-r from-purple-600 to-violet-500 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
            </div>
            <p class="text-center">
                <span id="progress-number" class="text-3xl font-display text-white">0</span>
                <span class="text-gray-400 text-sm">%</span>
            </p>
            <p id="progress-status" class="text-xs text-gray-500 text-center">Preparing upload...</p>
        </div>

        {{-- Brand Color --}}
        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-5">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                Brand Primary Color
            </h2>
            <p class="text-sm text-gray-400">Controls the accent color used across the entire website — buttons, links, highlights, and gradients.</p>

            {{-- Presets --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Quick Presets</p>
                <div class="flex flex-wrap gap-3" id="color-presets">
                    @php
                        $colorPresets = [
                            '#7c3aed' => 'Purple',
                            '#2563eb' => 'Blue',
                            '#0891b2' => 'Cyan',
                            '#059669' => 'Emerald',
                            '#dc2626' => 'Red',
                            '#d97706' => 'Amber',
                            '#db2777' => 'Pink',
                            '#0284c7' => 'Sky',
                        ];
                    @endphp
                    @foreach($colorPresets as $hex => $label)
                    <button type="button" onclick="setBrandColor('{{ $hex }}')" title="{{ $label }}"
                        class="w-9 h-9 rounded-full transition-all hover:scale-110 ring-2 ring-offset-2 ring-offset-gray-900 ring-white/0 hover:ring-white/40"
                        style="background-color: {{ $hex }}"></button>
                    @endforeach
                </div>
            </div>

            {{-- Color picker --}}
            <div class="flex items-center gap-5">
                <label class="relative cursor-pointer group">
                    <input type="color" name="primary_color" id="primary-color-input"
                        value="{{ old('primary_color', $settings['primary_color']) }}"
                        class="w-16 h-16 rounded-xl cursor-pointer border-2 border-white/10 group-hover:border-white/30 transition-colors p-1 bg-gray-800">
                </label>
                <div>
                    <p class="text-sm font-medium text-white mb-1">Custom Color</p>
                    <p class="text-xs text-gray-500 mb-2">Click the swatch to open picker, or choose a preset above</p>
                    <code class="text-sm font-mono text-purple-400" id="color-hex-display">{{ old('primary_color', $settings['primary_color']) }}</code>
                </div>
            </div>
            @error('primary_color') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Text Settings --}}
        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Hero Texts
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title Line 1</label>
                    <input type="text" name="hero_title_line1" value="{{ old('hero_title_line1', $settings['hero_title_line1']) }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="KHAYREDDINE">
                    @error('hero_title_line1') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title Line 2</label>
                    <input type="text" name="hero_title_line2" value="{{ old('hero_title_line2', $settings['hero_title_line2']) }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="3D ARTIST">
                    @error('hero_title_line2') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title Line 3</label>
                    <input type="text" name="hero_title_line3" value="{{ old('hero_title_line3', $settings['hero_title_line3']) }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="FX DESIGNER">
                    @error('hero_title_line3') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea name="hero_description" rows="3" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('hero_description', $settings['hero_description']) }}</textarea>
                @error('hero_description') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Site Logo --}}
        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-4">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                Site Logo
            </h2>
            @if($settings['site_logo'])
            <div class="flex items-center gap-4">
                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Current logo" class="h-14 object-contain rounded-lg border border-white/10 bg-white/5 px-3 py-2">
                <div class="text-sm text-gray-400">
                    <p>Current logo</p>
                    <p class="text-xs text-gray-500 mt-1">Upload a new image to replace it</p>
                </div>
            </div>
            @else
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center font-bold text-2xl">F</div>
                <p class="text-sm text-gray-400">Using default gradient logo. Upload an image to replace it.</p>
            </div>
            @endif
            <input type="file" name="site_logo" accept="image/*"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none file:bg-purple-600 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-1 file:mr-4">
            <p class="text-xs text-gray-500">PNG, SVG, or WEBP recommended · Max 2MB · Will appear in the navbar and footer</p>
            @error('site_logo') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Portrait Image --}}
        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-4">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Portrait Image
            </h2>
            @if($settings['hero_portrait'])
            <div class="flex items-start gap-4">
                <img src="{{ asset('storage/' . $settings['hero_portrait']) }}" alt="Current portrait" class="w-32 h-40 object-cover rounded-xl border border-white/10">
                <div class="text-sm text-gray-400">
                    <p>Current portrait image</p>
                    <p class="text-xs text-gray-500 mt-1">Upload a new image to replace</p>
                </div>
            </div>
            @endif
            <input type="file" name="hero_portrait" accept="image/*"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none file:bg-purple-600 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-1 file:mr-4">
            @error('hero_portrait') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Intro Video --}}
        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-4">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Intro Video
            </h2>
            @if($settings['intro_video'])
            <div class="flex items-start gap-4">
                <video src="{{ asset('storage/' . $settings['intro_video']) }}" class="w-64 rounded-xl border border-white/10" controls muted></video>
                <div class="text-sm text-gray-400">
                    <p>Current intro video</p>
                    <p class="text-xs text-gray-500 mt-1">Upload a new video to replace</p>
                </div>
            </div>
            @endif
            <input type="file" name="intro_video" accept="video/mp4,video/webm,video/quicktime"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none file:bg-purple-600 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-1 file:mr-4">
            <p class="text-xs text-gray-500">Accepted formats: MP4, WebM, MOV · Max 1G</p>
            @error('intro_video') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-8 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-purple-500/25">
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
function setBrandColor(hex) {
    document.getElementById('primary-color-input').value = hex;
    document.getElementById('color-hex-display').textContent = hex;
}
document.getElementById('primary-color-input').addEventListener('input', function() {
    document.getElementById('color-hex-display').textContent = this.value;
});

// Upload progress with XHR
document.getElementById('hero-form').addEventListener('submit', function(e) {
    const fileInputs = this.querySelectorAll('input[type="file"]');
    let hasFile = false;
    fileInputs.forEach(input => { if (input.files.length > 0) hasFile = true; });
    if (!hasFile) return; // normal submit if no files

    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const progressDiv = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressNumber = document.getElementById('progress-number');
    const progressStatus = document.getElementById('progress-status');
    const submitBtn = form.querySelector('button[type="submit"]');

    progressDiv.classList.remove('hidden');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Uploading...';
    progressDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });

    const xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    let currentPercent = 0;
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            currentPercent = percent;
            progressBar.style.width = percent + '%';
            progressNumber.textContent = percent;
            if (percent < 100) {
                progressStatus.textContent = 'Uploading... ' + formatBytes(e.loaded) + ' / ' + formatBytes(e.total);
            } else {
                progressStatus.textContent = 'Processing... Please wait.';
            }
        }
    });

    xhr.addEventListener('load', function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            progressStatus.textContent = 'Upload complete! Redirecting...';
            progressNumber.textContent = '100';
            progressBar.style.width = '100%';
            window.location.reload();
        } else {
            progressStatus.textContent = 'Upload failed. Please try again.';
            progressBar.classList.remove('from-purple-600', 'to-violet-500');
            progressBar.classList.add('bg-red-500');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Settings';
        }
    });

    xhr.addEventListener('error', function() {
        progressStatus.textContent = 'Connection error. Please try again.';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save Settings';
    });

    xhr.send(formData);
});

function formatBytes(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}
</script>
@endsection
