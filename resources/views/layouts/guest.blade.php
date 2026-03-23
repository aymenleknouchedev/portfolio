<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'FraxionFX') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-gray-950 text-white antialiased">
        <div class="min-h-screen flex relative overflow-hidden">
            {{-- Animated Background --}}
            <div class="absolute inset-0 bg-gradient-to-br from-gray-950 via-purple-950/20 to-gray-950"></div>
            <div class="absolute inset-0">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-1/4 right-1/3 w-80 h-80 bg-violet-500/8 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
                <div class="absolute top-1/2 right-1/4 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
            </div>

            {{-- Left Panel: Branding (hidden on mobile) --}}
            <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center p-12">
                <div class="relative z-10 max-w-md">
                    <a href="/" class="flex items-center space-x-3 group mb-12">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center font-bold text-2xl group-hover:scale-110 transition-transform shadow-xl shadow-purple-500/30">F</div>
                        <span class="text-3xl font-bold">FraxionFX</span>
                    </a>
                    <h2 class="text-4xl font-bold leading-tight mb-4">
                        Premium <span class="bg-gradient-to-r from-purple-400 to-violet-400 bg-clip-text text-transparent">3D Assets</span> & Visual Effects
                    </h2>
                    <p class="text-gray-400 text-lg leading-relaxed mb-8">
                        Access exclusive add-ons, tutorials, and professional-grade assets crafted for creators and studios worldwide.
                    </p>
                    <div class="flex items-center gap-6 text-sm text-gray-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Premium Add-ons
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            In-depth Tutorials
                        </div>
                    </div>
                </div>

                {{-- Decorative elements --}}
                <div class="absolute bottom-12 left-12 text-xs text-gray-600">© {{ date('Y') }} FraxionFX</div>
            </div>

            {{-- Right Panel: Form --}}
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center relative z-10 p-6 sm:p-12">
                {{-- Mobile Logo --}}
                <div class="lg:hidden mb-8">
                    <a href="/" class="flex items-center space-x-3 group">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center font-bold text-xl group-hover:scale-110 transition-transform">F</div>
                        <span class="text-2xl font-bold">FraxionFX</span>
                    </a>
                </div>

                <div class="w-full max-w-md">
                    {{-- Glass Card --}}
                    <div class="backdrop-blur-2xl bg-white/[0.03] border border-white/[0.06] rounded-3xl p-8 sm:p-10 shadow-2xl shadow-black/20">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
