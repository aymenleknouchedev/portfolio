@extends('layouts.app')

@section('content')
{{-- Hero Section with Intro Video --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20" id="hero">
    {{-- Animated Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-gray-950 via-purple-950/20 to-gray-950"></div>
    <div class="absolute inset-0" id="particles-bg">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-violet-500/10 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 1s"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 2s"></div>
    </div>

    {{-- Grid Overlay --}}
    <div class="absolute inset-0 opacity-[0.02]"
        style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><rect width=%2260%22 height=%2260%22 fill=%22none%22 stroke=%22white%22 stroke-width=%220.5%22/></svg>');">
    </div>

    <div class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" id="hero-content">
        {{-- Hero Split: Text Left + Picture Right --}}
        <div class="flex flex-col-reverse lg:flex-row items-center gap-10 lg:gap-16 mb-10">
            {{-- Left: Text --}}
            <div class="flex-1 text-center lg:text-left">

                <h1 class="font-display text-5xl sm:text-6xl  lg:text-8xl leading-[0.9] mb-6 mt-15" data-aos="fade-up"
                    data-aos-delay="300">
                    <span class="text-white">{{ $hero['title_line1'] }}</span><br>
                    <span
                        class="bg-gradient-to-r from-purple-400 via-violet-400 to-purple-500 bg-clip-text text-transparent">{{ $hero['title_line2'] }}</span>
                    <span class="text-gray-500"> & </span>
                    <span class="bg-gradient-to-r from-violet-400 to-purple-500 bg-clip-text text-transparent">{{ $hero['title_line3'] }}</span>
                </h1>

                <p class="text-lg sm:text-xl text-gray-400 max-w-xl mb-8 leading-relaxed lg:mx-0 mx-auto"
                    data-aos="fade-up" data-aos-delay="400">
                    {{ $hero['description'] }}
                </p>
            </div>

            {{-- Right: Profile Picture --}}
            <div class="shrink-0 mt-20 lg:mt-0" data-aos="fade-left" data-aos-delay="400">
                <div class="relative">
                    {{-- Glow effect --}}
                    <div
                        class="absolute -inset-4 bg-gradient-to-br from-purple-500/30 to-violet-600/30 rounded-[3rem] blur-2xl opacity-60 animate-pulse">
                    </div>

                    {{-- Glass frame --}}
                    <div class="relative glass-card-strong p-1.5 rounded-[3rem]">
                        <div
                            class="w-56 h-80 sm:w-64 sm:h-96 lg:w-72 lg:h-[30rem] rounded-[1.1rem] overflow-hidden bg-gradient-to-br from-purple-900/50 to-gray-900">
                            @if($hero['portrait'])
                            <img src="{{ asset('storage/' . $hero['portrait']) }}" alt="{{ $hero['title_line1'] }} - {{ $hero['title_line2'] }} & {{ $hero['title_line3'] }}"
                                class="w-full h-full object-cover">
                            @else
                            <img src="{{ asset('images/profile.jpg') }}" alt="{{ $hero['title_line1'] }}"
                                class="w-full h-full object-cover"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            {{-- Fallback if image not found --}}
                            <div class="w-full h-full items-center justify-center hidden" style="display: flex;">
                                <span class="font-display text-7xl text-purple-400">{{ substr($hero['title_line1'], 0, 1) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Floating badges --}}
                    <div class="absolute -right-2 top-8 glass rounded-xl px-3 py-1.5 text-xs font-medium text-purple-300 animate-bounce"
                        style="animation-delay: 0.5s; animation-duration: 3s;">
                        ✦ Blender
                    </div>
                    <div class="absolute -left-4 bottom-12 glass rounded-xl px-3 py-1.5 text-xs font-medium text-purple-300 animate-bounce"
                        style="animation-delay: 1s; animation-duration: 3.5s;">
                        ✦ Houdini
                    </div>
                    <div class="absolute right-4 -bottom-2 glass rounded-xl px-3 py-1.5 text-xs font-medium text-purple-300 animate-bounce"
                        style="animation-delay: 1.5s; animation-duration: 4s;">
                        ✦ After Effects
                    </div>
                </div>
            </div>
        </div>

        {{-- Intro Video 16:9 with Glassmorphism --}}
        <div class="glass-card-strong p-2 sm:p-3" data-aos="fade-up" data-aos-delay="500">
            <div class="relative aspect-video rounded-xl overflow-hidden bg-gray-900">
                @if($hero['intro_video'])
                <video id="hero-video" class="w-full h-full object-cover" loop playsinline>
                    <source src="{{ asset('storage/' . $hero['intro_video']) }}" type="video/mp4">
                </video>
                {{-- Buffering/Loading Overlay --}}
                <div id="video-loading" class="absolute inset-0 bg-black/50 flex items-center justify-center z-20 hidden pointer-events-none">
                    <div class="text-center">
                        <div class="w-14 h-14 mx-auto mb-3 relative">
                            <svg class="w-14 h-14 animate-spin" viewBox="0 0 50 50">
                                <circle class="opacity-20" cx="25" cy="25" r="20" stroke="white" stroke-width="4" fill="none"></circle>
                                <circle cx="25" cy="25" r="20" stroke="url(#loader-gradient)" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="80, 200" stroke-dashoffset="0"></circle>
                                <defs><linearGradient id="loader-gradient"><stop offset="0%" stop-color="#a855f7"/><stop offset="100%" stop-color="#7c3aed"/></linearGradient></defs>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-300">Loading...</p>
                    </div>
                </div>
                {{-- Play overlay --}}
                <div id="video-play-overlay" class="absolute inset-0 bg-black/30 flex items-center justify-center cursor-pointer z-10 transition-opacity duration-300"
                    onclick="toggleVideoPlayPause()">
                    <div class="w-20 h-20 rounded-full glass flex items-center justify-center hover:scale-110 transition-transform">
                        <svg id="play-icon" class="w-8 h-8 text-purple-400 ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        <svg id="pause-icon" class="w-8 h-8 text-purple-400 hidden" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                        </svg>
                    </div>
                </div>
                <script>
                    function toggleVideoPlayPause() {
                        const video = document.getElementById('hero-video');
                        const overlay = document.getElementById('video-play-overlay');
                        const playIcon = document.getElementById('play-icon');
                        const pauseIcon = document.getElementById('pause-icon');

                        if (video.paused) {
                            video.play();
                            overlay.style.opacity = '0';
                            overlay.style.pointerEvents = 'none';
                            playIcon.classList.add('hidden');
                            pauseIcon.classList.remove('hidden');
                        } else {
                            video.pause();
                            overlay.style.opacity = '1';
                            overlay.style.pointerEvents = 'auto';
                            playIcon.classList.remove('hidden');
                            pauseIcon.classList.add('hidden');
                        }
                    }

                    // Add click handler to video element
                    document.getElementById('hero-video').addEventListener('click', toggleVideoPlayPause);

                    // Show overlay when video ends
                    document.getElementById('hero-video').addEventListener('ended', () => {
                        const overlay = document.getElementById('video-play-overlay');
                        const playIcon = document.getElementById('play-icon');
                        const pauseIcon = document.getElementById('pause-icon');
                        overlay.style.opacity = '1';
                        overlay.style.pointerEvents = 'auto';
                        playIcon.classList.remove('hidden');
                        pauseIcon.classList.add('hidden');
                    });

                    // Buffering / loading detection
                    (function() {
                        const video = document.getElementById('hero-video');
                        const loader = document.getElementById('video-loading');
                        if (!video || !loader) return;

                        let bufferTimer = null;

                        video.addEventListener('waiting', () => {
                            bufferTimer = setTimeout(() => loader.classList.remove('hidden'), 300);
                        });

                        video.addEventListener('playing', () => {
                            clearTimeout(bufferTimer);
                            loader.classList.add('hidden');
                        });

                        video.addEventListener('canplay', () => {
                            clearTimeout(bufferTimer);
                            loader.classList.add('hidden');
                        });
                    })();
                </script>
                @else
                <video id="hero-video" class="w-full h-full object-cover" loop playsinline poster="">
                </video>
                @endif

                {{-- Video Placeholder / Overlay when no video source --}}
                @if(!$hero['intro_video'])
                <div id="video-placeholder"
                    class="absolute inset-0 bg-gradient-to-br from-purple-900/40 via-gray-900 to-violet-900/40 flex items-center justify-center">
                    <div class="text-center">
                        <div
                            class="w-20 h-20 mx-auto rounded-full glass flex items-center justify-center mb-4 cursor-pointer hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-purple-400 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </div>
                        <p class="text-gray-400 text-sm">Intro Showreel</p>
                    </div>
                </div>
                @endif

                {{-- Bottom glassmorphic overlay bar --}}
                <div class="absolute bottom-0 inset-x-0 glass p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center text-xs font-bold">
                            F</div>
                        <div>
                            <p class="text-sm font-medium text-white">FraxionFX Showreel 2025</p>
                            <p class="text-xs text-gray-400">3D & VFX Highlights</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="sound-toggle" onclick="toggleVideoSound()"
                            class="hidden sm:inline-flex items-center gap-2 text-sm bg-white/10 hover:bg-white/20 text-white px-3 py-2 rounded-lg transition-all">
                            <svg id="sound-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H3a2 2 0 01-2-2V9a2 2 0 012-2h2.586l5.657-5.657a1 1 0 011.414 0l5.657 5.657h2.586a2 2 0 012 2v4a2 2 0 01-2 2h-2.586l-5.657 5.657a1 1 0 01-1.414 0L5.586 15z" />
                            </svg>
                            <span class="text-xs">On</span>
                        </button>
                        <a href="#portfolio"
                            class="hidden sm:inline-flex items-center gap-2 text-sm bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-all">
                            View Work
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="#shop"
                            class="inline-flex items-center gap-2 text-sm bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg transition-all hover:shadow-lg hover:shadow-purple-500/25">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Shop Now
                        </a>
                    </div>
                </div>
                <script>
                    let isMuted = false;
                    
                    function toggleVideoSound() {
                        const video = document.getElementById('hero-video');
                        const soundToggle = document.getElementById('sound-toggle');
                        const soundIcon = document.getElementById('sound-icon');
                        
                        isMuted = !isMuted;
                        video.muted = isMuted;
                        
                        if (isMuted) {
                            soundToggle.classList.add('bg-white/5');
                            soundToggle.querySelector('span').textContent = 'Off';
                            soundIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H3a2 2 0 01-2-2V9a2 2 0 012-2h2.586l5.657-5.657a1 1 0 011.414 0l5.657 5.657h2.586a2 2 0 012 2v4a2 2 0 01-2 2h-2.586l-5.657 5.657a1 1 0 01-1.414 0L5.586 15z M3 3l18 18"/>';
                        } else {
                            soundToggle.classList.remove('bg-white/5');
                            soundToggle.querySelector('span').textContent = 'On';
                            soundIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H3a2 2 0 01-2-2V9a2 2 0 012-2h2.586l5.657-5.657a1 1 0 011.414 0l5.657 5.657h2.586a2 2 0 012 2v4a2 2 0 01-2 2h-2.586l-5.657 5.657a1 1 0 01-1.414 0L5.586 15z"/>';
                        }
                    }
                </script>
            </div>
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <div class="w-6 h-10 rounded-full border-2 border-white/20 flex items-start justify-center p-1">
            <div class="w-1.5 h-3 rounded-full bg-purple-400 animate-pulse"></div>
        </div>
    </div>
</section>

{{-- Brands Marquee --}}
@if($brands->count())
<section class="relative mt-16 py-8 overflow-hidden border-y border-white/5 bg-gray-950/80">
    <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-transparent to-gray-950 z-10 pointer-events-none"></div>
    <div class="flex items-center gap-16 animate-marquee whitespace-nowrap">
        @for($i = 0; $i < 2; $i++)
        @foreach($brands as $brand)
        <div class="flex items-center gap-3 shrink-0 opacity-40 hover:opacity-80 transition-opacity duration-300">
            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-8 w-auto object-contain grayscale hover:grayscale-0 transition-all duration-300">
            <span class="text-sm text-gray-500 font-medium tracking-wide">{{ $brand->name }}</span>
        </div>
        @endforeach
        @endfor
    </div>
    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>
</section>
@endif

{{-- Featured Work / Portfolio --}}
<section class="py-24 px-4" id="portfolio">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-16" data-aos="fade-up">
            <div>
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">Portfolio</span>
                <h2 class="font-display text-5xl lg:text-6xl mt-2">FEATURED WORK</h2>
            </div>
            <a href="{{ route('portfolio.index') }}"
                class="hidden sm:inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 transition-colors font-medium">
                View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $project)
            <a href="{{ route('portfolio.show', $project->slug) }}"
                class="group glass-card overflow-hidden hover:border-purple-500/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-purple-500/10"
                data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="aspect-video bg-gradient-to-br from-purple-900/30 to-gray-800 overflow-hidden">
                    @if($project->hero_image)
                    <img src="{{ asset('storage/' . $project->hero_image) }}" alt="{{ $project->title }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <span class="text-xs text-purple-400 font-medium uppercase tracking-wider">{{ $project->projectCategory->name ?? $project->category
                        }}</span>
                    <h3 class="text-lg font-semibold mt-2 group-hover:text-purple-400 transition-colors">{{
                        $project->title }}</h3>
                    <p class="text-gray-400 text-sm mt-2 line-clamp-2">{{ strip_tags($project->description) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Shop Now Section --}}
<section class="py-24 px-4 relative" id="shop">
    <div class="absolute inset-0 bg-gradient-to-b from-purple-950/10 via-gray-950 to-gray-950"></div>
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">Digital Assets</span>
            <h2 class="font-display text-5xl lg:text-6xl mt-2">PREMIUM ADD-ONS</h2>
            <p class="text-gray-400 mt-4 max-w-xl mx-auto">Professional-grade assets and presets to supercharge your 3D
                workflow.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredAddons as $addon)
            <div class="group glass-card overflow-hidden hover:border-purple-500/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-purple-500/10"
                data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" x-data="{ hovering: false }"
                @mouseenter="hovering = true" @mouseleave="hovering = false">
                <div class="aspect-video bg-gradient-to-br from-purple-900/20 to-gray-800 overflow-hidden relative">
                    @if($addon->demo_video_url)
                    @php
                        $addonUrl = $addon->demo_video_url;
                        $addonVideoId = null;
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $addonUrl, $m)) {
                            $addonVideoId = $m[1];
                        } elseif (preg_match('/youtube\.com\/embed\/([\w-]+)/', $addonUrl, $m)) {
                            $addonVideoId = $m[1];
                        }
                        $addonEmbedUrl = $addonVideoId ? 'https://www.youtube.com/embed/' . $addonVideoId : $addonUrl;
                    @endphp
                    <iframe x-show="hovering" :src="hovering ? '{{ $addonEmbedUrl }}?autoplay=1&mute=1' : ''"
                        class="w-full h-full absolute inset-0" frameborder="0" allow="autoplay" allowfullscreen></iframe>
                    @endif
                    <div class="w-full h-full flex items-center justify-center" x-show="!hovering">
                        @if($addon->cover_image)
                        <img src="{{ asset('storage/' . $addon->cover_image) }}" alt="{{ $addon->name }}" class="w-full h-full object-cover">
                        @else
                        <svg class="w-16 h-16 text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @endif
                    </div>
                    <div class="absolute top-3 left-3">
                        @if($addon->is_featured)
                        <span class="glass text-green-400 text-xs font-bold px-3 py-1 rounded-full">Featured</span>
                        @endif
                    </div>
                    <div class="absolute top-3 right-3">
                        @if($addon->price <= 0)
                        <span class="glass text-green-400 text-xs font-bold px-3 py-1 rounded-full">Free</span>
                        @else
                        <span class="glass text-amber-300 text-xs font-bold px-3 py-1 rounded-full">${{
                            number_format($addon->price, 2) }}</span>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    <span class="text-xs text-purple-400 font-medium uppercase tracking-wider">{{ $addon->category->name
                        }}</span>
                    <h3 class="text-lg font-semibold mt-2">{{ $addon->name }}</h3>
                    <p class="text-gray-400 text-sm mt-2 line-clamp-2">{{ $addon->description }}</p>
                    <div class="flex items-center gap-3 mt-6">
                        <a href="{{ route('shop.show', $addon->slug) }}"
                            class="flex-1 text-center text-sm glass hover:bg-white/10 text-white py-2.5 rounded-lg transition-all">View
                            Details</a>
                        @if($addon->price <= 0)
                        <a href="{{ route('download.free', $addon->slug) }}"
                            class="flex-1 text-center text-sm bg-green-600 hover:bg-green-500 text-white py-2.5 rounded-lg transition-all hover:shadow-lg hover:shadow-green-500/25">Download
                            Free</a>
                        @else
                        <a href="{{ route('checkout.show', $addon->slug) }}"
                            class="flex-1 text-center text-sm bg-purple-600 hover:bg-purple-500 text-white py-2.5 rounded-lg transition-all hover:shadow-lg hover:shadow-purple-500/25">Buy
                            Now</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12" data-aos="fade-up">
            <a href="{{ route('shop.index') }}"
                class="inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 transition-colors font-medium">
                Browse All Add-ons <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- Services Section --}}
<section class="py-24 px-4" id="services">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">Services</span>
            <h2 class="font-display text-5xl lg:text-6xl mt-2">WHAT I CAN DO FOR YOU</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($services as $service)
            <div class="group glass-card p-8 hover:border-purple-500/20 transition-all duration-300" data-aos="fade-up"
                data-aos-delay="{{ $loop->index * 100 }}">
                <div class="flex items-start gap-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500/20 to-violet-500/20 border border-purple-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 4V2M17 4V2M3 8h18M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold mb-2">{{ $service->title }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed mb-4">{{ $service->description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-purple-400 font-medium text-sm">{{ $service->price_range }}</span>
                            <a href="{{ $service->getWhatsappUrl() }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-all hover:shadow-lg hover:shadow-green-500/20">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                Hire Me on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Latest Articles --}}
<section class="py-24 px-4 relative" id="learn">
    <div class="absolute inset-0 bg-gradient-to-b from-gray-950 via-purple-950/5 to-gray-950"></div>
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="flex items-center justify-between mb-16" data-aos="fade-up">
            <div>
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">Learn</span>
                <h2 class="font-display text-5xl lg:text-6xl mt-2">LATEST ARTICLES</h2>
            </div>
            <a href="{{ route('learn.index') }}"
                class="hidden sm:inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 transition-colors font-medium">
                View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($articles as $article)
            <a href="{{ route('learn.show', $article->slug) }}"
                class="group glass-card overflow-hidden hover:border-purple-500/20 transition-all duration-300 hover:-translate-y-1"
                data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="aspect-video bg-gradient-to-br from-purple-900/20 to-gray-800">
                    @if($article->hero_image)
                    <img src="{{ asset('storage/' . $article->hero_image) }}" alt="{{ $article->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <time class="text-xs text-gray-500">{{ $article->published_at?->format('M d, Y') }}</time>
                    <h3 class="text-lg font-semibold mt-2 group-hover:text-purple-400 transition-colors">{{
                        $article->title }}</h3>
                    <p class="text-gray-400 text-sm mt-2 line-clamp-2">{{ $article->excerpt }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- About / Studio Section --}}
<section class="py-24 px-4" id="about">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">About</span>
                <h2 class="font-display text-5xl lg:text-6xl mt-2 mb-6">{{ $about['title'] }}</h2>
                <p class="text-gray-400 leading-relaxed mb-6">{{ $about['description_1'] }}</p>
                <p class="text-gray-400 leading-relaxed mb-8">{{ $about['description_2'] }}</p>
                <div class="grid grid-cols-3 gap-6">
                    <div class="text-center p-4 glass-card">
                        <div class="font-display text-4xl text-purple-400">{{ $about['stat_1_number'] }}</div>
                        <div class="text-gray-400 text-sm mt-1">{{ $about['stat_1_label'] }}</div>
                    </div>
                    <div class="text-center p-4 glass-card">
                        <div class="font-display text-4xl text-purple-400">{{ $about['stat_2_number'] }}</div>
                        <div class="text-gray-400 text-sm mt-1">{{ $about['stat_2_label'] }}</div>
                    </div>
                    <div class="text-center p-4 glass-card">
                        <div class="font-display text-4xl text-purple-400">{{ $about['stat_3_number'] }}</div>
                        <div class="text-gray-400 text-sm mt-1">{{ $about['stat_3_label'] }}</div>
                    </div>
                </div>
            </div>
            <div class="relative" data-aos="fade-left">
                <div
                    class="aspect-square rounded-3xl glass-card-strong overflow-hidden flex items-center justify-center">
                    <div class="text-center p-8">
                        <div
                            class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center font-display text-6xl mb-6">
                            {{ substr($about['avatar_name'], 0, 1) }}</div>
                        <h3 class="font-display text-4xl">{{ $about['avatar_name'] }}</h3>
                        <p class="text-purple-400 mt-1">{{ $about['avatar_title'] }}</p>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-purple-500/20 rounded-full blur-2xl"></div>
            </div>
        </div>
    </div>
</section>

{{-- Contact / CTA Section --}}
<section class="py-24 px-4 relative" id="contact">
    <div class="absolute inset-0 bg-gradient-to-b from-gray-950 via-purple-950/10 to-gray-950"></div>
    <div class="max-w-3xl mx-auto relative z-10">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">Contact</span>
            <h2 class="font-display text-5xl lg:text-6xl mt-2">LET'S CREATE TOGETHER</h2>
            <p class="text-gray-400 mt-4">Have a project in mind? Let's bring your vision to life.</p>
        </div>

        <form action="{{ route('contact.submit') }}" method="POST" class="glass-card-strong p-8 sm:p-10 space-y-6"
            data-aos="fade-up" data-aos-delay="100">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <input type="text" name="name" placeholder="Your Name" required
                        class="w-full glass border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition-colors">
                    @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <input type="email" name="email" placeholder="Your Email" required
                        class="w-full glass border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition-colors">
                    @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <input type="text" name="subject" placeholder="Subject" required
                    class="w-full glass border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition-colors">
                @error('subject') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <textarea name="message" rows="5" placeholder="Your Message" required
                    class="w-full glass border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition-colors resize-none"></textarea>
                @error('message') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="text-center">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-500 text-white font-semibold px-10 py-4 rounded-xl transition-all hover:shadow-xl hover:shadow-purple-500/25">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</section>
@endsection