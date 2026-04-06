@extends('layouts.admin')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">About Section</h1>
            <p class="text-gray-400 text-sm mt-1">Manage the about/studio section on the homepage</p>
        </div>
    </div>

    {{-- Settings Navigation --}}
    <div class="flex gap-2 mb-8 flex-wrap">
        <a href="{{ route('admin.settings.hero') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Hero</a>
        <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">General</a>
        <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Social Media</a>
        <a href="{{ route('admin.settings.about') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-purple-600 text-white">About Section</a>
        <a href="{{ route('admin.settings.account') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Account</a>
        <a href="{{ route('admin.settings.payment') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Payment</a>
    </div>

    <form action="{{ route('admin.settings.about.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-purple-400"></i>
                Content
            </h2>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Section Title</label>
                <input type="text" name="about_title" value="{{ old('about_title', $settings['about_title']) }}" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                    placeholder="THE STUDIO">
                @error('about_title') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">First Paragraph</label>
                <textarea name="about_description_1" rows="4" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('about_description_1', $settings['about_description_1']) }}</textarea>
                @error('about_description_1') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Second Paragraph</label>
                <textarea name="about_description_2" rows="3"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('about_description_2', $settings['about_description_2']) }}</textarea>
                @error('about_description_2') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-chart-simple text-purple-400"></i>
                Statistics
            </h2>
            <p class="text-sm text-gray-400">Three stat cards displayed in the about section.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Number 1</label>
                        <input type="text" name="about_stat_1_number" value="{{ old('about_stat_1_number', $settings['about_stat_1_number']) }}" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-purple-500 focus:outline-none"
                            placeholder="50+">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Label 1</label>
                        <input type="text" name="about_stat_1_label" value="{{ old('about_stat_1_label', $settings['about_stat_1_label']) }}" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-purple-500 focus:outline-none"
                            placeholder="Projects">
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Number 2</label>
                        <input type="text" name="about_stat_2_number" value="{{ old('about_stat_2_number', $settings['about_stat_2_number']) }}" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-purple-500 focus:outline-none"
                            placeholder="5+">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Label 2</label>
                        <input type="text" name="about_stat_2_label" value="{{ old('about_stat_2_label', $settings['about_stat_2_label']) }}" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-purple-500 focus:outline-none"
                            placeholder="Years">
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Number 3</label>
                        <input type="text" name="about_stat_3_number" value="{{ old('about_stat_3_number', $settings['about_stat_3_number']) }}" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-purple-500 focus:outline-none"
                            placeholder="100+">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Label 3</label>
                        <input type="text" name="about_stat_3_label" value="{{ old('about_stat_3_label', $settings['about_stat_3_label']) }}" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-purple-500 focus:outline-none"
                            placeholder="Clients">
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-user text-purple-400"></i>
                Avatar Card
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                    <input type="text" name="about_avatar_name" value="{{ old('about_avatar_name', $settings['about_avatar_name']) }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="KHAYREDDINE">
                    @error('about_avatar_name') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title / Role</label>
                    <input type="text" name="about_avatar_title" value="{{ old('about_avatar_title', $settings['about_avatar_title']) }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="3D Artist & FX Designer">
                    @error('about_avatar_title') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
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
