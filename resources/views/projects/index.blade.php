@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">Our Work</span>
            <h1 class="text-4xl lg:text-6xl font-bold mt-2">Portfolio</h1>
            <p class="text-gray-400 mt-4 max-w-xl mx-auto">Explore our latest projects, from cinematic VFX to stunning 3D environments.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-3 mb-12" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('portfolio.index') }}"
               class="px-5 py-2 rounded-full text-sm font-medium transition-all {{ !request('category') ? 'bg-purple-600 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                All
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('portfolio.index', ['category' => $cat->slug]) }}"
               class="px-5 py-2 rounded-full text-sm font-medium transition-all {{ request('category') === $cat->slug ? 'bg-purple-600 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $project)
            <a href="{{ route('portfolio.show', $project->slug) }}" class="group rounded-2xl overflow-hidden bg-gray-900 border border-white/5 hover:border-purple-500/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-purple-500/10" data-aos="fade-up" data-aos-delay="{{ $loop->index % 3 * 100 }}">
                <div class="aspect-video bg-gradient-to-br from-purple-900/30 to-gray-800 overflow-hidden">
                    @if($project->hero_image)
                        <img src="{{ asset('storage/' . $project->hero_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <span class="text-xs text-purple-400 font-medium uppercase tracking-wider">{{ $project->projectCategory->name ?? $project->category }}</span>
                    <h3 class="text-lg font-semibold mt-2 group-hover:text-purple-400 transition-colors">{{ $project->title }}</h3>
                    <p class="text-gray-400 text-sm mt-2 line-clamp-2">{{ $project->description }}</p>
                    <div class="flex items-center gap-2 mt-4">
                        @if($project->software_used)
                            @foreach(array_slice($project->software_used, 0, 3) as $sw)
                            <span class="text-xs bg-white/5 border border-white/10 text-gray-400 px-2.5 py-1 rounded-md">{{ $sw }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection
