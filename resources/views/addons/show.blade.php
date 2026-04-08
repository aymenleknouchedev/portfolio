@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-5xl mx-auto">
        {{-- Download error flash --}}
        @if(session('download_error'))
        <div class="mb-6 flex items-center gap-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-xl px-5 py-4">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <span>{{ session('download_error') }}</span>
        </div>
        @endif

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-8" data-aos="fade-up">
            <a href="{{ route('shop.index') }}" class="hover:text-purple-400 transition-colors">Shop</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-300">{{ $addon->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
            {{-- Left: Video & Screenshots --}}
            <div class="lg:col-span-3" data-aos="fade-right">
                <div class="aspect-video rounded-2xl overflow-hidden bg-gray-900 border border-white/5 mb-6">
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
                        <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @elseif($addon->cover_image)
                        <img src="{{ asset('storage/' . $addon->cover_image) }}" alt="{{ $addon->name }}" class="w-full h-full object-cover cursor-pointer" onclick="openAddonSingleImage('{{ asset('storage/' . $addon->cover_image) }}')">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-900/20 to-gray-800">
                            <svg class="w-20 h-20 text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                        </div>
                    @endif
                </div>

                {{-- Single Image Fullscreen Modal --}}
                <div id="addon-single-lightbox" class="fixed inset-0 z-[9999] bg-black/95 backdrop-blur-xl hidden items-center justify-center p-4" onclick="closeAddonSingleImage()">
                    <div class="relative" onclick="event.stopPropagation()">
                        <img id="addon-single-lightbox-img" src="" alt="Full view" class="max-w-full max-h-[90vh] rounded-xl object-contain select-none mx-auto block">
                    </div>
                    <button onclick="closeAddonSingleImage()" class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors z-10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <script>
                window.openAddonSingleImage = function(src) {
                    var lb = document.getElementById('addon-single-lightbox');
                    document.getElementById('addon-single-lightbox-img').src = src;
                    lb.classList.remove('hidden');
                    lb.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                };
                window.closeAddonSingleImage = function() {
                    var lb = document.getElementById('addon-single-lightbox');
                    lb.classList.add('hidden');
                    lb.classList.remove('flex');
                    document.body.style.overflow = '';
                };
                document.addEventListener('keydown', function(e) {
                    var lb = document.getElementById('addon-single-lightbox');
                    if (lb && !lb.classList.contains('hidden') && e.key === 'Escape') closeAddonSingleImage();
                });
                </script>

                {{-- Screenshots --}}
                @if($addon->screenshots && count($addon->screenshots) > 0)
                <div class="grid grid-cols-3 gap-3">
                    @foreach($addon->screenshots as $index => $screenshot)
                    <div onclick="openAddonLightbox({{ $index }})" class="aspect-video rounded-lg overflow-hidden bg-gray-800 cursor-pointer hover:ring-2 hover:ring-purple-500 transition-all">
                        <img src="{{ asset('storage/' . $screenshot) }}" alt="Screenshot {{ $index + 1 }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>

                {{-- Fullscreen Image Lightbox --}}
                <div id="addon-lightbox" class="fixed inset-0 z-[9999] bg-black/95 backdrop-blur-xl hidden items-center justify-center p-4" onclick="closeAddonLightbox()">
                    <div class="relative" onclick="event.stopPropagation()">
                        <img id="addon-lightbox-img" src="" alt="Screenshot" class="max-w-full max-h-[90vh] rounded-xl object-contain select-none mx-auto block">
                    </div>
                    <div id="addon-lightbox-counter" class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/60 text-sm font-medium"></div>
                    <button onclick="closeAddonLightbox()" class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors z-10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <button id="addon-lb-prev" onclick="event.stopPropagation(); addonLightboxNav(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors z-10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button id="addon-lb-next" onclick="event.stopPropagation(); addonLightboxNav(1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors z-10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                <script>
                (function() {
                    const urls = [
                        @foreach($addon->screenshots as $screenshot)
                        "{{ asset('storage/' . $screenshot) }}",
                        @endforeach
                    ];
                    let current = 0;
                    window.openAddonLightbox = function(index) {
                        current = index;
                        render();
                        const lb = document.getElementById('addon-lightbox');
                        lb.classList.remove('hidden');
                        lb.classList.add('flex');
                        document.body.style.overflow = 'hidden';
                    };
                    window.closeAddonLightbox = function() {
                        const lb = document.getElementById('addon-lightbox');
                        lb.classList.add('hidden');
                        lb.classList.remove('flex');
                        document.body.style.overflow = '';
                    };
                    window.addonLightboxNav = function(dir) {
                        current = (current + dir + urls.length) % urls.length;
                        render();
                    };
                    function render() {
                        document.getElementById('addon-lightbox-img').src = urls[current];
                        document.getElementById('addon-lightbox-counter').textContent = (current + 1) + ' / ' + urls.length;
                    }
                    document.addEventListener('keydown', function(e) {
                        const lb = document.getElementById('addon-lightbox');
                        if (!lb || lb.classList.contains('hidden')) return;
                        if (e.key === 'Escape') closeAddonLightbox();
                        if (e.key === 'ArrowLeft') addonLightboxNav(-1);
                        if (e.key === 'ArrowRight') addonLightboxNav(1);
                    });
                })();
                </script>
                @endif

                {{-- Description --}}
                <div class="mt-8">
                    <h2 class="text-xl font-semibold mb-4">Description</h2>
                    <div class="prose prose-invert prose-purple max-w-none
                        prose-headings:font-bold prose-headings:text-white
                        prose-p:text-gray-400 prose-p:leading-relaxed
                        prose-a:text-purple-400 prose-a:no-underline hover:prose-a:text-purple-300
                        prose-strong:text-white
                        prose-li:text-gray-400
                        prose-ul:list-disc prose-ol:list-decimal">
                        {!! $addon->description !!}
                    </div>
                </div>
            </div>

            {{-- Right: Info & Buy --}}
            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="sticky top-28 space-y-6">
                    <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                        <span class="text-xs text-purple-400 font-medium uppercase tracking-wider">{{ $addon->category->name }}</span>
                        <h1 class="text-2xl font-bold mt-2">{{ $addon->name }}</h1>

                        <div class="flex items-center gap-2 mt-4">
                            @if($addon->badge_text)
                            <span class="inline-flex items-center gap-1 bg-blue-500/10 text-blue-400 text-xs font-medium px-3 py-1 rounded-full border border-blue-500/20">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                {{ $addon->badge_text }}
                            </span>
                            @endif
                        </div>

                        <div class="border-t border-white/5 mt-6 pt-6">
                            @if($addon->price <= 0)
                                <div class="text-4xl font-bold text-green-400">Free</div>
                                <a href="{{ route('download.free', $addon->slug) }}" class="block w-full text-center bg-green-600 hover:bg-green-500 text-white font-semibold py-4 rounded-xl mt-4 transition-all hover:shadow-xl hover:shadow-green-500/25">
                                    Download Free
                                </a>
                            @else
                                <div class="flex items-baseline gap-3">
                                    <span class="text-4xl font-bold text-white">${{ number_format($addon->price, 2) }}</span>
                                    @if($addon->original_price && $addon->original_price > $addon->price)
                                        <span class="text-xl text-gray-500 line-through">${{ number_format($addon->original_price, 2) }}</span>
                                        @php $discount = round((($addon->original_price - $addon->price) / $addon->original_price) * 100); @endphp
                                        <span class="text-sm font-semibold text-green-400 bg-green-500/10 px-2 py-0.5 rounded-full">-{{ $discount }}%</span>
                                    @endif
                                </div>
                                <a href="{{ route('checkout.show', $addon->slug) }}" class="block w-full text-center bg-purple-600 hover:bg-purple-500 text-white font-semibold py-4 rounded-xl mt-4 transition-all hover:shadow-xl hover:shadow-purple-500/25">
                                    Buy Now
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Features --}}
                    @if($addon->features && count($addon->features) > 0)
                    <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                        <h3 class="font-semibold mb-4">Features</h3>
                        <ul class="space-y-3">
                            @foreach($addon->features as $feature)
                            <li class="flex items-start gap-3 text-sm text-gray-300">
                                <svg class="w-5 h-5 text-purple-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Related --}}
        @if($relatedAddons->count() > 0)
        <div class="mt-20">
            <h2 class="text-2xl font-bold mb-8">Related Add-ons</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedAddons as $related)
                <a href="{{ route('shop.show', $related->slug) }}" class="group rounded-2xl overflow-hidden bg-gray-900 border border-white/5 hover:border-purple-500/20 transition-all">
                    <div class="aspect-video bg-gradient-to-br from-purple-900/20 to-gray-800 flex items-center justify-center">
                        @if($related->cover_image)
                            <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold group-hover:text-purple-400 transition-colors">{{ $related->name }}</h3>
                        @if($related->price <= 0)
                            <span class="text-green-400 font-medium text-sm">Free</span>
                        @else
                            <span class="text-purple-400 font-medium text-sm">${{ number_format($related->price, 2) }}</span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
