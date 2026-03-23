@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">About</span>
                <h1 class="text-4xl lg:text-6xl font-bold mt-2 mb-6">The Studio</h1>
                <p class="text-gray-400 leading-relaxed mb-6">FraxionFX is a creative studio specializing in cutting-edge 3D visual effects, particle simulations, and digital content creation. Every project is crafted with meticulous attention to detail and a passion for pushing creative boundaries.</p>
                <p class="text-gray-400 leading-relaxed mb-6">With expertise in Blender, Houdini, and modern rendering pipelines, we deliver stunning visuals that captivate audiences and elevate brands worldwide.</p>
                <p class="text-gray-400 leading-relaxed mb-8">Our mission is to democratize premium 3D content by offering high-quality add-ons, educational resources, and professional services to creators at every level.</p>
                <div class="grid grid-cols-3 gap-6">
                    <div class="text-center p-5 rounded-xl bg-white/5 border border-white/5">
                        <div class="text-3xl font-bold text-purple-400">50+</div>
                        <div class="text-gray-400 text-sm mt-1">Projects</div>
                    </div>
                    <div class="text-center p-5 rounded-xl bg-white/5 border border-white/5">
                        <div class="text-3xl font-bold text-purple-400">5+</div>
                        <div class="text-gray-400 text-sm mt-1">Years</div>
                    </div>
                    <div class="text-center p-5 rounded-xl bg-white/5 border border-white/5">
                        <div class="text-3xl font-bold text-purple-400">100+</div>
                        <div class="text-gray-400 text-sm mt-1">Clients</div>
                    </div>
                </div>
            </div>
            <div class="relative" data-aos="fade-left">
                <div class="aspect-square rounded-3xl bg-gradient-to-br from-purple-500/20 via-gray-800 to-violet-500/20 border border-white/5 flex items-center justify-center">
                    <div class="text-center p-8">
                        <div class="w-40 h-40 mx-auto rounded-full bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center text-6xl font-black mb-6">K</div>
                        <h3 class="text-3xl font-bold">Khayreddine</h3>
                        <p class="text-purple-400 mt-2 text-lg">3D Artist & FX Designer</p>
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
