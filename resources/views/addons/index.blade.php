@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">Digital Assets</span>
            <h1 class="text-4xl lg:text-6xl font-bold mt-2">Shop Now</h1>
        </div>

        {{-- Category Filter --}}
        <div class="flex flex-wrap justify-center gap-3 mb-12" data-aos="fade-up" data-aos-delay="100" x-data="{ activeCategory: 'all' }">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-purple-600 text-white border-purple-600' : 'bg-white/5 text-gray-300 border-white/10 hover:border-purple-500/30'" class="px-5 py-2 rounded-full text-sm font-medium border transition-all">
                All
            </button>
            @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category->slug }}'" :class="activeCategory === '{{ $category->slug }}' ? 'bg-purple-600 text-white border-purple-600' : 'bg-white/5 text-gray-300 border-white/10 hover:border-purple-500/30'" class="px-5 py-2 rounded-full text-sm font-medium border transition-all">
                {{ $category->name }}
            </button>
            @endforeach

            {{-- Addon Grid --}}
            <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                @foreach($addons as $addon)
                <div x-show="activeCategory === 'all' || activeCategory === '{{ $addon->category->slug }}'" x-transition class="group rounded-2xl overflow-hidden bg-gray-900 border border-white/5 hover:border-purple-500/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-purple-500/10" x-data="{ hovering: false }" @mouseenter="hovering = true" @mouseleave="hovering = false">
                    <div class="aspect-video bg-gradient-to-br from-purple-900/20 to-gray-800 overflow-hidden relative">
                        @if($addon->demo_video_url)
                            @php
                                $url = $addon->demo_video_url;
                                $videoId = null;
                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $url, $m)) {
                                    $videoId = $m[1];
                                } elseif (preg_match('/youtube\.com\/embed\/([\w-]+)/', $url, $m)) {
                                    $videoId = $m[1];
                                }
                                $embedUrl = $videoId ? 'https://www.youtube.com/embed/' . $videoId : $url;
                            @endphp
                            <iframe x-show="hovering" :src="hovering ? '{{ $embedUrl }}?autoplay=1&mute=1' : ''" class="w-full h-full absolute inset-0" frameborder="0" allow="autoplay" allowfullscreen></iframe>
                        @endif
                        <div class="w-full h-full flex items-center justify-center" x-show="!hovering">
                            @if($addon->cover_image)
                                <img src="{{ asset('storage/' . $addon->cover_image) }}" alt="{{ $addon->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-16 h-16 text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div class="absolute top-3 right-3">
                            @if($addon->price <= 0)
                                <span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full">Free</span>
                            @else
                                <span class="bg-purple-600 text-white text-xs font-bold px-3 py-1 rounded-full">${{ number_format($addon->price, 2) }}</span>
                            @endif
                        </div>
                        @if($addon->is_featured)
                        <div class="absolute top-3 left-3">
                            <span class="bg-amber-500/90 text-white text-xs font-bold px-3 py-1 rounded-full">Featured</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <span class="text-xs text-purple-400 font-medium uppercase tracking-wider">{{ $addon->category->name }}</span>
                        <h3 class="text-lg font-semibold mt-2">{{ $addon->name }}</h3>
                        <p class="text-gray-400 text-sm mt-2 line-clamp-2">{{ strip_tags($addon->description) }}</p>
                        <div class="flex items-center gap-3 mt-6">
                            <a href="{{ route('shop.show', $addon->slug) }}" class="flex-1 text-center text-sm bg-white/5 hover:bg-white/10 border border-white/10 text-white py-2.5 rounded-lg transition-all">View Details</a>
                            @if($addon->price <= 0)
                                <a href="{{ route('download.free', $addon->slug) }}" class="flex-1 text-center text-sm bg-green-600 hover:bg-green-500 text-white py-2.5 rounded-lg transition-all hover:shadow-lg hover:shadow-green-500/25">Download Free</a>
                            @else
                                <a href="{{ route('checkout.show', $addon->slug) }}" class="flex-1 text-center text-sm bg-purple-600 hover:bg-purple-500 text-white py-2.5 rounded-lg transition-all hover:shadow-lg hover:shadow-purple-500/25">Buy Now</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
