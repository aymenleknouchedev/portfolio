@extends('layouts.admin')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Login & Register Settings</h1>
            <p class="text-gray-400 text-sm mt-1">Configure the left panel content on login and register pages</p>
        </div>
    </div>

    {{-- Settings Navigation --}}
    <div class="flex gap-2 mb-8 flex-wrap">
        <a href="{{ route('admin.settings.hero') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Hero</a>
        <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">General</a>
        <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Social Media</a>
        <a href="{{ route('admin.settings.about') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">About Section</a>
        <a href="{{ route('admin.settings.auth') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-purple-600 text-white">Login & Register</a>
    </div>

    <form action="{{ route('admin.settings.auth.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-lock text-purple-400"></i>
                Left Panel Content
            </h2>
            <p class="text-sm text-gray-400">This content appears on the left side of the login and register pages (desktop view).</p>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Main Title</label>
                <input type="text" name="auth_title" value="{{ old('auth_title', $settings['auth_title']) }}" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                    placeholder="Premium 3D Assets & Visual Effects">
                <p class="text-xs text-gray-500 mt-2">Main heading displayed on login/register pages</p>
                @error('auth_title') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea name="auth_description" rows="3" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none"
                    placeholder="Access exclusive add-ons, tutorials, and professional-grade assets crafted for creators and studios worldwide.">{{ old('auth_description', $settings['auth_description']) }}</textarea>
                <p class="text-xs text-gray-500 mt-2">Subtitle/description text</p>
                @error('auth_description') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Feature 1</label>
                    <input type="text" name="auth_feature_1" value="{{ old('auth_feature_1', $settings['auth_feature_1']) }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="Premium Add-ons">
                    <p class="text-xs text-gray-500 mt-2">First feature/benefit</p>
                    @error('auth_feature_1') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Feature 2</label>
                    <input type="text" name="auth_feature_2" value="{{ old('auth_feature_2', $settings['auth_feature_2']) }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="In-depth Tutorials">
                    <p class="text-xs text-gray-500 mt-2">Second feature/benefit</p>
                    @error('auth_feature_2') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-8 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-purple-500/25">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
