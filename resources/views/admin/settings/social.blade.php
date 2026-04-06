@extends('layouts.admin')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Social Media Links</h1>
            <p class="text-gray-400 text-sm mt-1">Manage social media links displayed across the site</p>
        </div>
    </div>

    {{-- Settings Navigation --}}
    <div class="flex gap-2 mb-8 flex-wrap">
        <a href="{{ route('admin.settings.hero') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Hero</a>
        <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">General</a>
        <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-purple-600 text-white">Social Media</a>
        <a href="{{ route('admin.settings.about') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">About Section</a>
        <a href="{{ route('admin.settings.account') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Account</a>
        <a href="{{ route('admin.settings.payment') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Payment</a>
    </div>

    <form action="{{ route('admin.settings.social.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-share-nodes text-purple-400"></i>
                Social Media Profiles
            </h2>
            <p class="text-sm text-gray-400">Links appear in the top bar and footer. Leave blank to hide an icon.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-twitter mr-2 text-purple-400"></i>Twitter / X</label>
                    <input type="url" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://x.com/username">
                    @error('social_twitter') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-github mr-2 text-purple-400"></i>GitHub</label>
                    <input type="url" name="social_github" value="{{ old('social_github', $settings['social_github']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://github.com/username">
                    @error('social_github') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-instagram mr-2 text-purple-400"></i>Instagram</label>
                    <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://instagram.com/username">
                    @error('social_instagram') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-linkedin mr-2 text-purple-400"></i>LinkedIn</label>
                    <input type="url" name="social_linkedin" value="{{ old('social_linkedin', $settings['social_linkedin']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://linkedin.com/in/username">
                    @error('social_linkedin') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-youtube mr-2 text-purple-400"></i>YouTube</label>
                    <input type="url" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://youtube.com/@channel">
                    @error('social_youtube') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-behance mr-2 text-purple-400"></i>Behance</label>
                    <input type="url" name="social_behance" value="{{ old('social_behance', $settings['social_behance']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://behance.net/username">
                    @error('social_behance') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-facebook mr-2 text-purple-400"></i>Facebook</label>
                    <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://facebook.com/page">
                    @error('social_facebook') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-dribbble mr-2 text-purple-400"></i>Dribbble</label>
                    <input type="url" name="social_dribbble" value="{{ old('social_dribbble', $settings['social_dribbble']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://dribbble.com/username">
                    @error('social_dribbble') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-whatsapp mr-2 text-purple-400"></i>WhatsApp</label>
                    <input type="text" name="social_whatsapp" value="{{ old('social_whatsapp', $settings['social_whatsapp']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="213XXXXXXXXX">
                    <p class="text-xs text-gray-500 mt-2">Phone number with country code, digits only (e.g. 213XXXXXXXXX). Used for "Hire Me" buttons on services.</p>
                    @error('social_whatsapp') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><i class="fa-brands fa-artstation mr-2 text-purple-400"></i>ArtStation</label>
                    <input type="url" name="social_artstation" value="{{ old('social_artstation', $settings['social_artstation']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://www.artstation.com/username">
                    @error('social_artstation') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"><svg class="inline w-4 h-4 mr-2 text-purple-400" viewBox="0 0 24 24" fill="currentColor"><path d="M11.5 9.05C11.26 9.05 11.04 9.14 10.88 9.3L7.3 12.88C7.14 13.04 7.05 13.26 7.05 13.5C7.05 13.74 7.14 13.96 7.3 14.12L9.88 16.7C10.04 16.86 10.26 16.95 10.5 16.95C10.74 16.95 10.96 16.86 11.12 16.7L14.7 13.12C14.86 12.96 14.95 12.74 14.95 12.5C14.95 12.26 14.86 12.04 14.7 11.88L12.12 9.3C11.96 9.14 11.74 9.05 11.5 9.05M11.5 0C5.15 0 0 5.15 0 11.5C0 17.85 5.15 23 11.5 23C17.85 23 23 17.85 23 11.5C23 5.15 17.85 0 11.5 0M11.5 21C6.26 21 2 16.74 2 11.5C2 6.26 6.26 2 11.5 2C16.74 2 21 6.26 21 11.5C21 16.74 16.74 21 11.5 21Z"/></svg>Sketchfab</label>
                    <input type="url" name="social_sketchfab" value="{{ old('social_sketchfab', $settings['social_sketchfab']) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                        placeholder="https://sketchfab.com/username">
                    @error('social_sketchfab') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
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
