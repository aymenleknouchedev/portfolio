<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteName = \App\Models\Setting::get('site_name', 'FraxionFX');
        $favicon = \App\Models\Setting::get('favicon');
        $heroLine1 = \App\Models\Setting::get('hero_title_line1', 'KHAYREDDINE');
        $heroLine2 = \App\Models\Setting::get('hero_title_line2', '3D ARTIST');
        $heroLine3 = \App\Models\Setting::get('hero_title_line3', 'FX DESIGNER');
        $heroDesc = \App\Models\Setting::get('hero_description', 'Crafting cinematic visual effects, immersive 3D environments, and premium digital assets for creators worldwide.');
        $portrait = \App\Models\Setting::get('hero_portrait');
        $siteLogo = \App\Models\Setting::get('site_logo');
        $ogTitle = $title ?? $heroLine1 . ' — ' . $heroLine2 . ' & ' . $heroLine3 . ' | ' . $siteName;
        $ogDescription = $metaDescription ?? $heroLine1 . ' — ' . $heroDesc;
        $ogImage = $ogImage ?? ($portrait ? asset('storage/' . $portrait) : ($siteLogo ? asset('storage/' . $siteLogo) : ''));
    @endphp
    <title>{{ $title ?? $heroLine1 . ' — ' . $heroLine2 . ' & ' . $heroLine3 . ' | ' . $siteName }}</title>

    {{-- Favicon --}}
    @if($favicon)
    @php
        $faviconUrl = asset('storage/' . $favicon);
        $faviconExt = strtolower(pathinfo($favicon, PATHINFO_EXTENSION));
        $faviconType = match($faviconExt) {
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            default => 'image/png',
        };
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" type="{{ $faviconType }}">
    <link rel="icon" href="{{ $faviconUrl }}" sizes="32x32" type="{{ $faviconType }}">
    <link rel="icon" href="{{ $faviconUrl }}" sizes="16x16" type="{{ $faviconType }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="{{ $faviconType }}">
    @endif

    {{-- SEO Meta --}}
    <meta name="description" content="{{ $ogDescription }}">
    <meta name="keywords" content="{{ $heroLine1 }}, {{ $siteName }}, 3D artist, VFX, visual effects, motion graphics, 3D design, particle simulation, digital assets, Blender, Houdini">
    <meta name="author" content="{{ $heroLine1 }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph (Facebook, LinkedIn, WhatsApp, etc.) --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    @if($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    @if($ogImage)
    <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php $primaryColor = \App\Models\Setting::get('primary_color', '#7c3aed'); @endphp
    <style>
        :root {
            --clr-brand: {{ $primaryColor }};
            --color-purple-300: color-mix(in srgb, var(--clr-brand) 45%, white);
            --color-purple-400: color-mix(in srgb, var(--clr-brand) 65%, white);
            --color-purple-500: color-mix(in srgb, var(--clr-brand) 82%, white);
            --color-purple-600: var(--clr-brand);
            --color-purple-700: color-mix(in srgb, var(--clr-brand) 82%, black);
            --color-purple-800: color-mix(in srgb, var(--clr-brand) 70%, black);
            --color-purple-900: color-mix(in srgb, var(--clr-brand) 55%, black);
            --color-purple-950: color-mix(in srgb, var(--clr-brand) 40%, black);
            --color-violet-400: color-mix(in srgb, var(--clr-brand) 60%, white);
            --color-violet-500: color-mix(in srgb, var(--clr-brand) 78%, white);
            --color-violet-600: color-mix(in srgb, var(--clr-brand) 92%, black);
            --color-violet-900: color-mix(in srgb, var(--clr-brand) 50%, black);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #030712;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--color-purple-600);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-purple-500);
        }

        /* Text Selection */
        ::selection {
            background-color: var(--color-purple-500);
            color: #ffffff;
        }
    </style>

    {{-- JSON-LD Structured Data --}}
    @php
        $personSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $heroLine1,
            'jobTitle' => $heroLine2 . ' & ' . $heroLine3,
            'description' => $ogDescription,
            'url' => url('/'),
        ];
        if ($ogImage) $personSchema['image'] = $ogImage;
        if ($siteLogo) $personSchema['logo'] = asset('storage/' . $siteLogo);
        $sameAs = array_values(array_filter([
            \App\Models\Setting::get('social_twitter'),
            \App\Models\Setting::get('social_github'),
            \App\Models\Setting::get('social_instagram'),
            \App\Models\Setting::get('social_linkedin'),
            \App\Models\Setting::get('social_youtube'),
            \App\Models\Setting::get('social_behance'),
            \App\Models\Setting::get('social_facebook'),
            \App\Models\Setting::get('social_dribbble'),
            \App\Models\Setting::get('social_artstation'),
            \App\Models\Setting::get('social_sketchfab'),
        ]));
        if ($sameAs) $personSchema['sameAs'] = $sameAs;

        $websiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => url('/'),
            'description' => $ogDescription,
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($personSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
</head>

<body class="bg-gray-950 text-white font-sans antialiased overflow-x-hidden" x-data="{ mobileMenu: false }">

    {{-- Custom Cursor --}}
    <div id="cursor"
        class="fixed w-5 h-5 rounded-full border-2 pointer-events-none z-[9999] transition-transform duration-100 ease-out mix-blend-difference hidden md:block"
        style="transform: translate(-50%, -50%); border-color: var(--color-purple-500);"></div>
    <div id="cursor-dot"
        class="fixed w-1.5 h-1.5 rounded-full pointer-events-none z-[9999] hidden md:block"
        style="transform: translate(-50%, -50%); background-color: var(--color-purple-400);"></div>

    @php
        $contactEmail = \App\Models\Setting::get('contact_email');
        $contactPhone = \App\Models\Setting::get('contact_phone');
        $socialTwitter = \App\Models\Setting::get('social_twitter');
        $socialGithub = \App\Models\Setting::get('social_github');
        $socialInstagram = \App\Models\Setting::get('social_instagram');
        $socialLinkedin = \App\Models\Setting::get('social_linkedin');
        $socialYoutube = \App\Models\Setting::get('social_youtube');
        $socialBehance = \App\Models\Setting::get('social_behance');
        $socialWhatsapp = \App\Models\Setting::get('social_whatsapp');
        $whatsappUrl = $socialWhatsapp ? 'https://wa.me/' . preg_replace('/\D/', '', preg_replace('#^https?://wa\.me/#', '', trim($socialWhatsapp))) : null;
        $socialFacebook = \App\Models\Setting::get('social_facebook');
        $socialDribbble = \App\Models\Setting::get('social_dribbble');
        $socialArtstation = \App\Models\Setting::get('social_artstation');
        $socialSketchfab = \App\Models\Setting::get('social_sketchfab');
    @endphp

    {{-- Top Bar --}}
    <div class="fixed top-0 left-0 right-0 text-white text-xs py-2 z-[60] overflow-hidden" style="background-color: var(--clr-brand);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between overflow-hidden">
            <div class="flex items-center gap-4 sm:gap-6">
                @if($contactEmail)
                <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-1.5 hover:text-white/80 transition-colors">
                    <i class="fa-solid fa-envelope text-[10px]"></i>
                    <span class="hidden sm:inline">{{ $contactEmail }}</span>
                </a>
                @endif
                @if($contactPhone)
                <a href="tel:{{ $contactPhone }}" class="flex items-center gap-1.5 hover:text-white/80 transition-colors">
                    <i class="fa-solid fa-phone text-[10px]"></i>
                    <span class="hidden sm:inline">{{ $contactPhone }}</span>
                </a>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @if($socialTwitter)<a href="{{ $socialTwitter }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-x-twitter"></i></a>@endif
                @if($socialInstagram)<a href="{{ $socialInstagram }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-instagram"></i></a>@endif
                @if($socialLinkedin)<a href="{{ $socialLinkedin }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-linkedin-in"></i></a>@endif
                @if($socialYoutube)<a href="{{ $socialYoutube }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-youtube"></i></a>@endif
                @if($socialGithub)<a href="{{ $socialGithub }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-github"></i></a>@endif
                @if($socialBehance)<a href="{{ $socialBehance }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-behance"></i></a>@endif
                @if($socialFacebook)<a href="{{ $socialFacebook }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-facebook-f"></i></a>@endif
                @if($socialDribbble)<a href="{{ $socialDribbble }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-dribbble"></i></a>@endif
                @if($socialArtstation)<a href="{{ $socialArtstation }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-artstation"></i></a>@endif
                @if($socialSketchfab)<a href="{{ $socialSketchfab }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><svg class="w-3.5 h-3.5 inline" viewBox="0 0 24 24" fill="currentColor"><path d="M11.5 9.05C11.26 9.05 11.04 9.14 10.88 9.3L7.3 12.88C7.14 13.04 7.05 13.26 7.05 13.5C7.05 13.74 7.14 13.96 7.3 14.12L9.88 16.7C10.04 16.86 10.26 16.95 10.5 16.95C10.74 16.95 10.96 16.86 11.12 16.7L14.7 13.12C14.86 12.96 14.95 12.74 14.95 12.5C14.95 12.26 14.86 12.04 14.7 11.88L12.12 9.3C11.96 9.14 11.74 9.05 11.5 9.05M11.5 0C5.15 0 0 5.15 0 11.5C0 17.85 5.15 23 11.5 23C17.85 23 23 17.85 23 11.5C23 5.15 17.85 0 11.5 0M11.5 21C6.26 21 2 16.74 2 11.5C2 6.26 6.26 2 11.5 2C16.74 2 21 6.26 21 11.5C21 16.74 16.74 21 11.5 21Z"/></svg></a>@endif
                @if($whatsappUrl)<a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="hover:text-white/80 transition-colors"><i class="fa-brands fa-whatsapp"></i></a>@endif
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="main-nav" style="top: 32px;">
        <div class="glass-nav">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-10 w-auto object-contain group-hover:scale-110 transition-transform">
                        @else
                        <div
                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                            {{ substr($siteName, 0, 1) }}</div>
                        <span
                            class="text-xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">{{ $siteName }}</span>
                        @endif
                    </a>
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="{{ route('home') }}"
                            class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                        <a href="{{ route('portfolio.index') }}"
                            class="nav-link {{ request()->routeIs('portfolio.*') ? 'nav-link-active' : '' }}">Portfolio</a>
                        <a href="{{ route('shop.index') }}"
                            class="nav-link {{ request()->routeIs('shop.*') ? 'nav-link-active' : '' }}">
                            <span class="flex items-center gap-1.5">Shop Now <span
                                    class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span></span>
                        </a>
                        <a href="{{ route('services.index') }}"
                            class="nav-link {{ request()->routeIs('services.*') ? 'nav-link-active' : '' }}">Services</a>
                        <a href="{{ route('learn.index') }}"
                            class="nav-link {{ request()->routeIs('learn.*') ? 'nav-link-active' : '' }}">Learn</a>
                        <a href="{{ route('about') }}"
                            class="nav-link {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">About</a>
                        <a href="{{ route('contact.show') }}"
                            class="nav-link {{ request()->routeIs('contact.*') ? 'nav-link-active' : '' }}">Contact</a>
                    </div>
                    <div class="hidden lg:flex items-center space-x-3">
                        @guest
                        <a href="{{ route('login') }}"
                            class="text-sm text-gray-300 hover:text-white transition-colors px-4 py-2 flex items-center gap-1.5"><i class="fa-solid fa-bag-shopping text-xs"></i> My Purchases</a>
                        <a href="{{ route('register') }}"
                            class="text-sm bg-purple-600 hover:bg-purple-500 text-white px-5 py-2 rounded-lg transition-all hover:shadow-lg hover:shadow-purple-500/25">Get
                            Started</a>
                        @else
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('client.dashboard') }}"
                            class="text-sm text-gray-300 hover:text-white transition-colors px-4 py-2 flex items-center gap-1.5"><i class="fa-solid fa-bag-shopping text-xs"></i> My Purchases</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-sm text-gray-400 hover:text-white transition-colors px-3 py-2">Logout</button>
                        </form>
                        @endguest
                    </div>
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-white p-2">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div x-show="mobileMenu" x-transition class="lg:hidden backdrop-blur-xl bg-gray-950/95 border-b border-white/5"
            @click.outside="mobileMenu = false">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="{{ route('home') }}"
                    class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Home</a>
                <a href="{{ route('portfolio.index') }}"
                    class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Portfolio</a>
                <a href="{{ route('shop.index') }}"
                    class="block px-4 py-3 text-purple-400 font-medium hover:bg-white/5 rounded-lg">Shop Now</a>
                <a href="{{ route('services.index') }}"
                    class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Services</a>
                <a href="{{ route('learn.index') }}"
                    class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Learn</a>
                <a href="{{ route('about') }}"
                    class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">About</a>
                <a href="{{ route('contact.show') }}"
                    class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Contact</a>
                <div class="border-t border-white/10 mt-3 pt-3">
                    @guest
                    <a href="{{ route('login') }}"
                        class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg"><i class="fa-solid fa-bag-shopping mr-2 text-xs"></i>My Purchases</a>
                    <a href="{{ route('register') }}"
                        class="block px-4 py-3 text-purple-400 hover:bg-purple-500/10 rounded-lg">Get Started</a>
                    @else
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('client.dashboard') }}"
                        class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg"><i class="fa-solid fa-bag-shopping mr-2 text-xs"></i>My Purchases</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="fixed top-28 right-4 z-50 bg-green-500/20 border border-green-500/30 text-green-300 px-6 py-3 rounded-xl backdrop-blur-sm"
        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
        {{ session('success') }}
    </div>
    @endif

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-950 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 mb-4">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-10 w-auto object-contain">
                        @else
                        <div
                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center font-bold">
                            {{ substr($siteName, 0, 1) }}</div>
                        <span class="text-xl font-bold">{{ $siteName }}</span>
                        @endif
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ Str::limit(\App\Models\Setting::get('about_description_1', 'Crafting stunning 3D visuals and digital experiences.'), 120) }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Explore</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('portfolio.index') }}"
                                class="text-gray-400 hover:text-purple-400 transition-colors">Portfolio</a></li>
                        <li><a href="{{ route('shop.index') }}"
                                class="text-gray-400 hover:text-purple-400 transition-colors">Shop</a></li>
                        <li><a href="{{ route('services.index') }}"
                                class="text-gray-400 hover:text-purple-400 transition-colors">Services</a></li>
                        <li><a href="{{ route('learn.index') }}"
                                class="text-gray-400 hover:text-purple-400 transition-colors">Learn</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('about') }}"
                                class="text-gray-400 hover:text-purple-400 transition-colors">About</a></li>
                        <li><a href="{{ route('contact.show') }}"
                                class="text-gray-400 hover:text-purple-400 transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Connect</h4>
                    <div class="flex flex-wrap gap-3">
                        @if($socialTwitter)
                        <a href="{{ $socialTwitter }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        @endif
                        @if($socialGithub)
                        <a href="{{ $socialGithub }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-github"></i>
                        </a>
                        @endif
                        @if($socialInstagram)
                        <a href="{{ $socialInstagram }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        @endif
                        @if($socialLinkedin)
                        <a href="{{ $socialLinkedin }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                        @endif
                        @if($socialYoutube)
                        <a href="{{ $socialYoutube }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                        @endif
                        @if($socialBehance)
                        <a href="{{ $socialBehance }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-behance"></i>
                        </a>
                        @endif
                        @if($socialFacebook)
                        <a href="{{ $socialFacebook }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        @endif
                        @if($socialDribbble)
                        <a href="{{ $socialDribbble }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-dribbble"></i>
                        </a>
                        @endif
                        @if($socialArtstation)
                        <a href="{{ $socialArtstation }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-artstation"></i>
                        </a>
                        @endif
                        @if($socialSketchfab)
                        <a href="{{ $socialSketchfab }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.5 9.05C11.26 9.05 11.04 9.14 10.88 9.3L7.3 12.88C7.14 13.04 7.05 13.26 7.05 13.5C7.05 13.74 7.14 13.96 7.3 14.12L9.88 16.7C10.04 16.86 10.26 16.95 10.5 16.95C10.74 16.95 10.96 16.86 11.12 16.7L14.7 13.12C14.86 12.96 14.95 12.74 14.95 12.5C14.95 12.26 14.86 12.04 14.7 11.88L12.12 9.3C11.96 9.14 11.74 9.05 11.5 9.05M11.5 0C5.15 0 0 5.15 0 11.5C0 17.85 5.15 23 11.5 23C17.85 23 23 17.85 23 11.5C23 5.15 17.85 0 11.5 0M11.5 21C6.26 21 2 16.74 2 11.5C2 6.26 6.26 2 11.5 2C16.74 2 21 6.26 21 11.5C21 16.74 16.74 21 11.5 21Z"/></svg>
                        </a>
                        @endif
                        @if($whatsappUrl)
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-white/5 mt-12 pt-8 text-center">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
    @stack('scripts')
</body>

</html>