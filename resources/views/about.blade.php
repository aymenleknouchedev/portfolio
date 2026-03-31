@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">About</span>
                <h1 class="text-4xl lg:text-6xl font-bold mt-2 mb-6">{{ $about['title'] }}</h1>
                <p class="text-gray-400 leading-relaxed mb-6">{{ $about['description_1'] }}</p>
                <p class="text-gray-400 leading-relaxed mb-8">{{ $about['description_2'] }}</p>
                <div class="grid grid-cols-3 gap-6">
                    <div class="text-center p-5 rounded-xl bg-white/5 border border-white/5">
                        <div class="text-3xl font-bold text-purple-400">{{ $about['stat_1_number'] }}</div>
                        <div class="text-gray-400 text-sm mt-1">{{ $about['stat_1_label'] }}</div>
                    </div>
                    <div class="text-center p-5 rounded-xl bg-white/5 border border-white/5">
                        <div class="text-3xl font-bold text-purple-400">{{ $about['stat_2_number'] }}</div>
                        <div class="text-gray-400 text-sm mt-1">{{ $about['stat_2_label'] }}</div>
                    </div>
                    <div class="text-center p-5 rounded-xl bg-white/5 border border-white/5">
                        <div class="text-3xl font-bold text-purple-400">{{ $about['stat_3_number'] }}</div>
                        <div class="text-gray-400 text-sm mt-1">{{ $about['stat_3_label'] }}</div>
                    </div>
                </div>
            </div>
            <div class="relative" data-aos="fade-left">
                <div class="aspect-square rounded-3xl bg-gradient-to-br from-purple-500/20 via-gray-800 to-violet-500/20 border border-white/5 flex items-center justify-center">
                    <div class="text-center p-8">
                        <div class="w-40 h-40 mx-auto rounded-full bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center text-6xl font-black mb-6">{{ substr($about['avatar_name'], 0, 1) }}</div>
                        <h3 class="text-3xl font-bold">{{ $about['avatar_name'] }}</h3>
                        <p class="text-purple-400 mt-2 text-lg">{{ $about['avatar_title'] }}</p>
                    </div>
                </div>
                <div class="absolute -bottom-8 -right-8 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-violet-500/10 rounded-full blur-2xl"></div>
            </div>
        </div>

        {{-- Skills --}}
        <div class="mt-24 grid grid-cols-2 md:grid-cols-4 gap-6" data-aos="fade-up">
            @foreach(['Blender', 'Houdini', 'After Effects', 'DaVinci Resolve', 'Substance Painter', 'ZBrush', 'Unity', 'Unreal Engine'] as $skill)
            <div class="text-center p-5 rounded-xl bg-gray-900/50 border border-white/5 hover:border-purple-500/20 transition-colors">
                <span class="text-gray-300 text-sm font-medium">{{ $skill }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
