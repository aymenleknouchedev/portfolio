<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $primaryColor = \App\Models\Setting::get('primary_color', '#7c3aed');
        $siteName = \App\Models\Setting::get('site_name', 'FraxionFX');
        $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count();
        $favicon = \App\Models\Setting::get('favicon');
    @endphp
    <title>Admin - {{ $siteName }}</title>
    @if($favicon)
    <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/png">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        /* Sidebar labels: hidden when collapsed, shown when expanded */
        .sidebar-label { display: none; white-space: nowrap; overflow: hidden; }
        .sidebar-expanded .sidebar-label { display: inline; }
        /* Prevent flash of unstyled content */
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-950 text-white font-sans antialiased overflow-x-hidden" x-data="adminSidebar()" @resize.window.debounce.100ms="onResize()">
    <div class="flex min-h-screen relative">

        {{-- Mobile overlay backdrop --}}
        <div x-show="mobileMenu" x-cloak
            x-transition:enter="transition-opacity duration-200 ease-out"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-150 ease-in"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="closeMobile()"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside x-ref="sidebar"
            :class="sidebarClasses()"
            class="fixed top-0 left-0 h-screen z-50 bg-gray-900 border-r border-white/5 shrink-0 overflow-y-auto overflow-x-hidden flex flex-col lg:sticky lg:z-auto -translate-x-full lg:translate-x-0 w-64 sidebar-expanded">
            <div class="p-4 border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" @click="closeMobile()" class="flex items-center gap-3">
                    <span class="sidebar-label text-lg font-bold">{{ $siteName }}</span>
                </a>
            </div>
            <nav class="p-3 space-y-1 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-gauge w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Dashboard</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.categories.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-tags w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Categories</span>
                </a>
                <a href="{{ route('admin.addons.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.addons.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-puzzle-piece w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Add-ons</span>
                </a>
                <a href="{{ route('admin.project-categories.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.project-categories.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-folder-tree w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Project Categories</span>
                </a>
                <a href="{{ route('admin.projects.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.projects.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-briefcase w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Projects</span>
                </a>
                <a href="{{ route('admin.services.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.services.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-concierge-bell w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Services</span>
                </a>
                <a href="{{ route('admin.brands.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.brands.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-handshake w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Brands</span>
                </a>
                <a href="{{ route('admin.articles.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.articles.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-newspaper w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Articles</span>
                </a>
                <a href="{{ route('admin.purchases.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.purchases.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-credit-card w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Purchases</span>
                </a>
                <a href="{{ route('admin.contact-messages.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.contact-messages.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors relative">
                    <i class="fa-solid fa-envelope w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Messages</span>
                    @if($unreadMessages > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center sidebar-label">{{ $unreadMessages }}</span>
                        <span x-show="!isExpanded()" class="absolute top-1.5 left-7 w-2 h-2 bg-red-500 rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('admin.waitlist.index') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.waitlist.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-users w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Waitlist</span>
                </a>
                <a href="{{ route('admin.settings.hero') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.settings.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-gear w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">Settings</span>
                </a>
                <div class="border-t border-white/5 my-3"></div>
                <a href="{{ route('home') }}" @click="closeMobile()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-white/5 hover:text-white transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center shrink-0"></i>
                    <span class="sidebar-label">View Site</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-white/5 hover:text-white transition-colors">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center shrink-0"></i>
                        <span class="sidebar-label">Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 min-w-0">
            <header class="sticky top-0 z-10 backdrop-blur-xl bg-gray-950/80 border-b border-white/5">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4">
                    <div class="flex items-center gap-3">
                        {{-- Mobile hamburger --}}
                        <button @click="mobileMenu = !mobileMenu" class="text-gray-400 hover:text-white lg:hidden">
                            <i class="fa-solid fa-bars text-xl"></i>
                        </button>
                        {{-- Desktop sidebar toggle --}}
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white hidden lg:block">
                            <i class="fa-solid fa-bars text-xl"></i>
                        </button>
                    </div>
                    <span class="text-sm text-gray-400">{{ auth()->user()->name }}</span>
                </div>
            </header>

            @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 bg-green-500/20 border border-green-500/30 text-green-300 px-5 py-3 rounded-xl"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
                {{ session('success') }}
            </div>
            @endif

            <div class="p-4 sm:p-6">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function adminSidebar() {
            const LG = 1024;
            return {
                sidebarOpen: window.innerWidth >= LG,
                mobileMenu: false,

                init() {
                    // Enable transitions only after Alpine has rendered the correct state
                    setTimeout(() => {
                        this.$refs.sidebar.style.transition = 'all 300ms ease-in-out';
                    }, 50);
                },

                isMobile() {
                    return window.innerWidth < LG;
                },

                isExpanded() {
                    // On mobile the sidebar is always full-width when open
                    if (this.isMobile()) return true;
                    // On desktop, controlled by the toggle button
                    return this.sidebarOpen;
                },

                sidebarClasses() {
                    let classes = [];
                    // Width + label visibility
                    if (this.isExpanded()) {
                        classes.push('w-64', 'sidebar-expanded');
                    } else {
                        classes.push('w-20');
                    }
                    // Slide position for mobile vs desktop
                    if (this.isMobile()) {
                        classes.push(this.mobileMenu ? 'translate-x-0' : '-translate-x-full');
                    } else {
                        classes.push('translate-x-0');
                    }
                    return classes.join(' ');
                },

                closeMobile() {
                    if (this.isMobile()) {
                        this.mobileMenu = false;
                    }
                },

                onResize() {
                    if (this.isMobile()) {
                        // Went to mobile: close the mobile overlay
                        this.mobileMenu = false;
                    } else {
                        // Went to desktop: close any mobile state
                        this.mobileMenu = false;
                    }
                }
            };
        }
    </script>
</body>

</html>