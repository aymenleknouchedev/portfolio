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
        $unreadReclamations = \App\Models\Reclamation::where('is_read_admin', false)->count();
        $favicon = \App\Models\Setting::get('favicon');

        // Page heading defaults — derive from current route when @section is missing
        $routeName = optional(request()->route())->getName() ?? '';
        $routeMap = [
            'admin.dashboard' => 'Dashboard',
            'admin.categories' => 'Categories',
            'admin.addons' => 'Add-ons',
            'admin.project-categories' => 'Project Categories',
            'admin.projects' => 'Projects',
            'admin.services' => 'Services',
            'admin.brands' => 'Brands',
            'admin.articles' => 'Articles',
            'admin.purchases' => 'Purchases',
            'admin.promo-codes' => 'Promo Codes',
            'admin.users' => 'Users',
            'admin.contact-messages' => 'Contact Messages',
            'admin.reclamations' => 'Reclamations',
            'admin.waitlist' => 'Waitlist',
            'admin.settings' => 'Settings',
        ];
        $routeFallback = 'Admin';
        foreach ($routeMap as $prefix => $label) {
            if (str_starts_with($routeName, $prefix)) { $routeFallback = $label; break; }
        }
        $pageTitle = trim($__env->yieldContent('page_title')) ?: $routeFallback;
        $pageSubtitle = trim($__env->yieldContent('page_subtitle'));
    @endphp
    <title>{{ $pageTitle }} — Admin · {{ $siteName }}</title>
    @if($favicon)
    <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/png">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        body {
            background:
                radial-gradient(1200px 600px at 90% -10%, color-mix(in srgb, var(--clr-brand) 18%, transparent), transparent 60%),
                radial-gradient(900px 500px at -10% 110%, color-mix(in srgb, var(--clr-brand) 12%, transparent), transparent 60%),
                #07070b;
        }
        .sidebar-label { display: none; white-space: nowrap; overflow: hidden; }
        .sidebar-expanded .sidebar-label { display: inline; }
        .sidebar-group-label { display: none; }
        .sidebar-expanded .sidebar-group-label { display: block; }
        [x-cloak] { display: none !important; }

        /* Nav item base */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            color: rgb(156 163 175);
            transition: all 0.15s ease;
            position: relative;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.04);
            color: white;
        }
        .nav-item.active {
            background: linear-gradient(135deg, color-mix(in srgb, var(--clr-brand) 90%, transparent), color-mix(in srgb, var(--clr-brand) 70%, black));
            color: white;
            box-shadow: 0 6px 24px -8px color-mix(in srgb, var(--clr-brand) 60%, transparent);
        }
        .nav-item .nav-icon {
            width: 1.25rem;
            text-align: center;
            flex-shrink: 0;
        }

        /* Universal glass effect for every admin tab.
           Any solid gray card becomes translucent w/ backdrop blur. */
        .admin-main [class~="bg-gray-900"],
        .admin-main [class~="bg-gray-900\\/60"] {
            background-color: rgba(17, 17, 23, 0.55) !important;
            backdrop-filter: blur(14px) saturate(120%);
            -webkit-backdrop-filter: blur(14px) saturate(120%);
        }
        .admin-main [class~="bg-gray-950"] {
            background-color: rgba(7, 7, 11, 0.6) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .admin-main [class~="bg-white\\/5"] {
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        /* Force 100% width on the page's top-level wrapper / form. */
        .admin-main > .max-w-sm,
        .admin-main > .max-w-md,
        .admin-main > .max-w-lg,
        .admin-main > .max-w-xl,
        .admin-main > .max-w-2xl,
        .admin-main > .max-w-3xl,
        .admin-main > .max-w-4xl,
        .admin-main > .max-w-5xl,
        .admin-main > .max-w-6xl,
        .admin-main > .max-w-7xl {
            max-width: 100% !important;
            width: 100%;
        }
        .admin-main > form[class*="max-w-"] {
            max-width: 100% !important;
            width: 100%;
        }
    </style>
</head>

<body class="text-white font-sans antialiased overflow-x-hidden min-h-screen" x-data="adminSidebar()" @resize.window.debounce.100ms="onResize()">
    <div class="flex min-h-screen relative">

        {{-- Mobile overlay --}}
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
            class="fixed top-0 left-0 h-screen z-50 bg-gray-950/80 backdrop-blur-xl border-r border-white/5 shrink-0 overflow-y-auto overflow-x-hidden flex flex-col lg:sticky lg:z-auto -translate-x-full lg:translate-x-0 w-64 sidebar-expanded">

            {{-- Logo --}}
            <div class="p-5 border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" @click="closeMobile()" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-purple-500 to-violet-700 flex items-center justify-center font-bold text-sm shadow-lg shadow-purple-500/30 shrink-0">
                        {{ strtoupper(substr($siteName, 0, 1)) }}
                    </div>
                    <div class="sidebar-label min-w-0">
                        <div class="font-bold text-base leading-tight truncate">{{ $siteName }}</div>
                        <div class="text-[11px] text-gray-500 leading-tight">Admin Panel</div>
                    </div>
                </a>
            </div>

            <nav class="p-3 space-y-6 flex-1 overflow-y-auto">

                {{-- Overview --}}
                <div>
                    <div class="sidebar-group-label px-2 mb-2 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">Overview</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge nav-icon"></i>
                            <span class="sidebar-label">Dashboard</span>
                        </a>
                    </div>
                </div>

                {{-- Catalog --}}
                <div>
                    <div class="sidebar-group-label px-2 mb-2 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">Catalog</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.categories.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-tags nav-icon"></i>
                            <span class="sidebar-label">Categories</span>
                        </a>
                        <a href="{{ route('admin.addons.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.addons.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-puzzle-piece nav-icon"></i>
                            <span class="sidebar-label">Add-ons</span>
                        </a>
                        <a href="{{ route('admin.project-categories.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.project-categories.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-folder-tree nav-icon"></i>
                            <span class="sidebar-label">Project Categories</span>
                        </a>
                        <a href="{{ route('admin.projects.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-briefcase nav-icon"></i>
                            <span class="sidebar-label">Projects</span>
                        </a>
                        <a href="{{ route('admin.services.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-concierge-bell nav-icon"></i>
                            <span class="sidebar-label">Services</span>
                        </a>
                        <a href="{{ route('admin.brands.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-handshake nav-icon"></i>
                            <span class="sidebar-label">Brands</span>
                        </a>
                    </div>
                </div>

                {{-- Content --}}
                <div>
                    <div class="sidebar-group-label px-2 mb-2 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">Content</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.articles.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-newspaper nav-icon"></i>
                            <span class="sidebar-label">Articles</span>
                        </a>
                    </div>
                </div>

                {{-- Sales --}}
                <div>
                    <div class="sidebar-group-label px-2 mb-2 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">Sales</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.purchases.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-credit-card nav-icon"></i>
                            <span class="sidebar-label">Purchases</span>
                        </a>
                        <a href="{{ route('admin.promo-codes.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.promo-codes.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-ticket nav-icon"></i>
                            <span class="sidebar-label">Promo Codes</span>
                        </a>
                    </div>
                </div>

                {{-- Community --}}
                <div>
                    <div class="sidebar-group-label px-2 mb-2 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">Community</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.users.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-group nav-icon"></i>
                            <span class="sidebar-label">Users</span>
                        </a>
                        <a href="{{ route('admin.contact-messages.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-envelope nav-icon"></i>
                            <span class="sidebar-label">Messages</span>
                            @if($unreadMessages > 0)
                                <span class="ml-auto bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[1.25rem] h-5 px-1.5 flex items-center justify-center sidebar-label">{{ $unreadMessages }}</span>
                                <span x-show="!isExpanded()" class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </a>
                        <a href="{{ route('admin.reclamations.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.reclamations.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-circle-exclamation nav-icon"></i>
                            <span class="sidebar-label">Reclamations</span>
                            @if($unreadReclamations > 0)
                                <span class="ml-auto bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[1.25rem] h-5 px-1.5 flex items-center justify-center sidebar-label">{{ $unreadReclamations }}</span>
                                <span x-show="!isExpanded()" class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </a>
                        <a href="{{ route('admin.waitlist.index') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.waitlist.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users nav-icon"></i>
                            <span class="sidebar-label">Waitlist</span>
                        </a>
                    </div>
                </div>

                {{-- System --}}
                <div>
                    <div class="sidebar-group-label px-2 mb-2 text-[10px] font-semibold text-gray-600 uppercase tracking-wider">System</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.settings.hero') }}" @click="closeMobile()"
                            class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-gear nav-icon"></i>
                            <span class="sidebar-label">Settings</span>
                        </a>
                    </div>
                </div>
            </nav>

            {{-- Footer / User --}}
            <div class="p-3 border-t border-white/5">
                <a href="{{ route('home') }}" @click="closeMobile()"
                    class="nav-item">
                    <i class="fa-solid fa-arrow-up-right-from-square nav-icon"></i>
                    <span class="sidebar-label">View Site</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full nav-item text-left">
                        <i class="fa-solid fa-right-from-bracket nav-icon"></i>
                        <span class="sidebar-label">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 min-w-0 flex flex-col">
            {{-- Top bar --}}
            <header class="sticky top-0 z-30 backdrop-blur-xl bg-black/30 border-b border-white/5">
                <div class="flex items-center justify-between gap-4 px-5 sm:px-8 py-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <button @click="mobileMenu = !mobileMenu" class="text-gray-400 hover:text-white lg:hidden">
                            <i class="fa-solid fa-bars text-xl"></i>
                        </button>
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white hidden lg:block">
                            <i class="fa-solid fa-bars text-xl"></i>
                        </button>
                        <div class="min-w-0">
                            <h1 class="text-lg sm:text-xl font-bold truncate leading-tight">{{ $pageTitle }}</h1>
                            @if($pageSubtitle)
                            <p class="text-xs text-gray-500 leading-tight">{{ $pageSubtitle }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                        @if($unreadMessages + $unreadReclamations > 0)
                        <a href="{{ $unreadReclamations > 0 ? route('admin.reclamations.index') : route('admin.contact-messages.index') }}"
                            class="relative p-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white transition-all" title="Notifications">
                            <i class="fa-regular fa-bell"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-gray-950"></span>
                        </a>
                        @endif

                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 sm:pr-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-violet-700 flex items-center justify-center text-sm font-bold shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden sm:block text-sm text-gray-300 truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-gray-500 hidden sm:block"></i>
                            </button>
                            <div x-show="open" x-cloak x-transition
                                class="absolute right-0 top-full mt-2 w-56 rounded-xl bg-gray-900 border border-white/10 shadow-2xl shadow-black/50 overflow-hidden">
                                <div class="px-4 py-3 border-b border-white/5">
                                    <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
                                </div>
                                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                    <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center text-xs"></i>
                                    View Site
                                </a>
                                <a href="{{ route('admin.settings.hero') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                    <i class="fa-solid fa-gear w-4 text-center text-xs"></i>
                                    Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-white/5">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                                        <i class="fa-solid fa-right-from-bracket w-4 text-center text-xs"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash --}}
            @if(session('success'))
            <div class="mx-5 sm:mx-8 mt-5 flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 px-5 py-3.5 rounded-xl"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
                <i class="fa-solid fa-circle-check"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="mx-5 sm:mx-8 mt-5 flex items-center gap-3 bg-red-500/10 border border-red-500/20 text-red-300 px-5 py-3.5 rounded-xl"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition>
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
            @endif

            <main class="admin-main p-5 sm:p-8 flex-1 w-full">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function adminSidebar() {
            const LG = 1024;
            return {
                sidebarOpen: window.innerWidth >= LG,
                mobileMenu: false,
                init() {
                    setTimeout(() => {
                        this.$refs.sidebar.style.transition = 'all 300ms ease-in-out';
                    }, 50);
                },
                isMobile() { return window.innerWidth < LG; },
                isExpanded() {
                    if (this.isMobile()) return true;
                    return this.sidebarOpen;
                },
                sidebarClasses() {
                    let classes = [];
                    if (this.isExpanded()) {
                        classes.push('w-64', 'sidebar-expanded');
                    } else {
                        classes.push('w-20');
                    }
                    if (this.isMobile()) {
                        classes.push(this.mobileMenu ? 'translate-x-0' : '-translate-x-full');
                    } else {
                        classes.push('translate-x-0');
                    }
                    return classes.join(' ');
                },
                closeMobile() { if (this.isMobile()) this.mobileMenu = false; },
                onResize() {
                    this.mobileMenu = false;
                }
            };
        }
    </script>
</body>

</html>
