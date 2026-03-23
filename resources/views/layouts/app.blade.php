<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'FraxionFX') }}</title>
    <meta name="description"
        content="{{ $metaDescription ?? 'FraxionFX — 3D Artist & FX Designer. Digital add-ons, VFX services, and creative tutorials.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
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
</head>

<body class="bg-gray-950 text-white font-sans antialiased overflow-x-hidden" x-data="{ mobileMenu: false }">

    {{-- Custom Cursor --}}
    <div id="cursor"
        class="fixed w-5 h-5 rounded-full border-2 pointer-events-none z-[9999] transition-transform duration-100 ease-out mix-blend-difference hidden md:block"
        style="transform: translate(-50%, -50%); border-color: var(--color-purple-500);"></div>
    <div id="cursor-dot"
        class="fixed w-1.5 h-1.5 rounded-full pointer-events-none z-[9999] hidden md:block"
        style="transform: translate(-50%, -50%); background-color: var(--color-purple-400);"></div>

    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="main-nav">
        <div class="glass-nav">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        @php $siteLogo = \App\Models\Setting::get('site_logo'); @endphp
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="FraxionFX" class="h-10 w-auto object-contain group-hover:scale-110 transition-transform">
                        @else
                        <div
                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                            F</div>
                        <span
                            class="text-xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">FraxionFX</span>
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
                            class="text-sm text-gray-300 hover:text-white transition-colors px-4 py-2">Sign In</a>
                        <a href="{{ route('register') }}"
                            class="text-sm bg-purple-600 hover:bg-purple-500 text-white px-5 py-2 rounded-lg transition-all hover:shadow-lg hover:shadow-purple-500/25">Get
                            Started</a>
                        @else
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('client.dashboard') }}"
                            class="text-sm bg-white/10 hover:bg-white/15 text-white px-4 py-2 rounded-lg backdrop-blur transition-all">Dashboard</a>
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
                        class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Sign In</a>
                    <a href="{{ route('register') }}"
                        class="block px-4 py-3 text-purple-400 hover:bg-purple-500/10 rounded-lg">Get Started</a>
                    @else
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('client.dashboard') }}"
                        class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="fixed top-24 right-4 z-50 bg-green-500/20 border border-green-500/30 text-green-300 px-6 py-3 rounded-xl backdrop-blur-sm"
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
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="FraxionFX" class="h-10 w-auto object-contain">
                        @else
                        <div
                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center font-bold">
                            F</div>
                        <span class="text-xl font-bold">FraxionFX</span>
                        @endif
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed">Crafting stunning 3D visuals and digital
                        experiences.</p>
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
                    <div class="flex space-x-3">
                        <a href="#"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 hover:border-purple-500/30 flex items-center justify-center text-gray-400 hover:text-purple-400 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/5 mt-12 pt-8 text-center">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} FraxionFX. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    @stack('scripts')
</body>

</html>