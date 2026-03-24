@extends('layouts.admin')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">General Settings</h1>
            <p class="text-gray-400 text-sm mt-1">Site name and contact information</p>
        </div>
    </div>

    {{-- Settings Navigation --}}
    <div class="flex gap-2 mb-8 flex-wrap">
        <a href="{{ route('admin.settings.hero') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Hero</a>
        <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-purple-600 text-white">General</a>
        <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Social Media</a>
        <a href="{{ route('admin.settings.about') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">About Section</a>
    </div>

    <form action="{{ route('admin.settings.general.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-globe text-purple-400"></i>
                Website Identity
            </h2>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Site Name</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                    placeholder="FraxionFX">
                <p class="text-xs text-gray-500 mt-2">Appears in the browser tab, navigation, and footer</p>
                @error('site_name') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-address-card text-purple-400"></i>
                Contact Information
            </h2>
            <p class="text-sm text-gray-400">Displayed in the top bar of the website and used for contact form delivery.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="hello@example.com">
                    <p class="text-xs text-gray-500 mt-2">Contact form submissions will be sent to this email</p>
                    @error('contact_email') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="+1 234 567 890">
                    @error('contact_phone') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
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
