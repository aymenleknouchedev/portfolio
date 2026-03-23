<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    </style>
</head>

<body class="bg-gray-950 text-white font-sans antialiased" x-data="{ sidebarOpen: true }">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="bg-gray-900 border-r border-white/5 transition-all duration-300 shrink-0">
            <div class="p-4 border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    @php $siteLogo = \App\Models\Setting::get('site_logo'); @endphp
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="w-10 h-10 object-contain rounded-lg shrink-0">
                    @else
                    <div
                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center font-bold text-lg shrink-0">
                        F</div>
                    @endif
                    <span x-show="sidebarOpen" class="text-lg font-bold">Admin</span>
                </a>
            </div>
            <nav class="p-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                    </svg>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.categories.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span x-show="sidebarOpen">Categories</span>
                </a>
                <a href="{{ route('admin.addons.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.addons.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span x-show="sidebarOpen">Add-ons</span>
                </a>
                <a href="{{ route('admin.project-categories.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.project-categories.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span x-show="sidebarOpen">Project Categories</span>
                </a>
                <a href="{{ route('admin.projects.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.projects.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span x-show="sidebarOpen">Projects</span>
                </a>
                <a href="{{ route('admin.services.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.services.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span x-show="sidebarOpen">Services</span>
                </a>
                <a href="{{ route('admin.articles.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.articles.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen">Articles</span>
                </a>
                <a href="{{ route('admin.purchases.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.purchases.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span x-show="sidebarOpen">Purchases</span>
                </a>
                <a href="{{ route('admin.waitlist.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.waitlist.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-show="sidebarOpen">Waitlist</span>
                </a>
                <a href="{{ route('admin.settings.hero') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.settings.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-show="sidebarOpen">Settings</span>
                </a>
                <div class="border-t border-white/5 my-3"></div>
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-white/5 hover:text-white transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span x-show="sidebarOpen">View Site</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-white/5 hover:text-white transition-colors">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="sidebarOpen">Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 overflow-auto">
            <header class="sticky top-0 z-10 backdrop-blur-xl bg-gray-950/80 border-b border-white/5">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <span class="text-sm text-gray-400">{{ auth()->user()->name }}</span>
                </div>
            </header>

            @if(session('success'))
            <div class="mx-6 mt-4 bg-green-500/20 border border-green-500/30 text-green-300 px-5 py-3 rounded-xl"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
                {{ session('success') }}
            </div>
            @endif

            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>