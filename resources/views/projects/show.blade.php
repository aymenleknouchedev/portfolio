@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="mb-8" data-aos="fade-up">
            <a href="{{ route('portfolio.index') }}"
                class="text-sm text-gray-400 hover:text-purple-400 transition-colors inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Portfolio
            </a>
        </div>

        <span class="text-purple-400 text-sm font-medium uppercase tracking-wider" data-aos="fade-up">{{
            $project->projectCategory->name ?? $project->category }}</span>
        <h1 class="text-3xl lg:text-2xl font-bold mt-2 mb-6" data-aos="fade-up" data-aos-delay="100">{{ $project->title
            }}</h1>

        <div class="flex flex-wrap items-center gap-4 mt-2">
            @if($project->published_at)
            <time class="text-gray-500 text-sm" data-aos="fade-up">{{ $project->published_at->format('F j, Y') }}</time>
            @endif

            @if($project->url)
            <a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer" data-aos="fade-up"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-purple-500/25"
                style="background: var(--clr-brand, #7c3aed);">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                Visit Project
            </a>
            @endif
        </div>

        {{-- Hero --}}
        <div class="mt-8 aspect-video rounded-2xl overflow-hidden bg-gray-900 border border-white/5" data-aos="fade-up"
            data-aos-delay="200">
            @if($project->hero_video)
            @php
                $videoUrl = $project->hero_video;
                $isYoutube = preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m);
                if ($isYoutube) {
                    $videoUrl = 'https://www.youtube.com/embed/' . $m[1];
                }
            @endphp
            @if($isYoutube)
            <iframe src="{{ $videoUrl }}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @else
            <video src="{{ $videoUrl }}" class="w-full h-full object-cover" controls playsinline>
                Your browser does not support the video tag.
            </video>
            @endif
            @elseif($project->hero_image)
            <img src="{{ asset('storage/' . $project->hero_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
            @else
            <div
                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-900/20 to-gray-800">
                <svg class="w-20 h-20 text-purple-500/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            @endif
        </div>

        {{-- Gallery --}}
        @if($project->gallery && count($project->gallery) > 0)
        <div class="mt-12" data-aos="fade-up">
            <h2 class="text-xl font-semibold mb-6">Gallery</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($project->gallery as $index => $img)
                <div class="group cursor-pointer gallery-thumb" data-index="{{ $index }}">
                    <div class="aspect-square rounded-xl overflow-hidden bg-gray-900 border border-white/5 hover:border-purple-500/30 transition-all duration-300">
                        <img src="{{ asset('storage/' . $img) }}" alt="{{ $project->title }} gallery image {{ $index + 1 }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Fullscreen Lightbox --}}
        <div id="lightbox" class="fixed inset-0 z-[9999] bg-black/95 backdrop-blur-xl items-center justify-center p-4 hidden">
            <button id="lb-close" class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors z-10">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <button id="lb-prev" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors z-10">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button id="lb-next" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors z-10">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <img id="lb-img" src="" alt="Gallery image" class="max-w-full max-h-[90vh] rounded-xl object-contain select-none">
            <div id="lb-counter" class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/60 text-sm font-medium"></div>
        </div>

        <script>
        (function() {
            var images = @json(array_map(fn($img) => asset('storage/' . $img), $project->gallery));
            var current = 0;
            var lb = document.getElementById('lightbox');
            var lbImg = document.getElementById('lb-img');
            var lbCounter = document.getElementById('lb-counter');

            function show(index) {
                current = index;
                lbImg.src = images[current];
                lbCounter.textContent = (current + 1) + ' / ' + images.length;
                lb.classList.remove('hidden');
                lb.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            function hide() {
                lb.classList.add('hidden');
                lb.classList.remove('flex');
                document.body.style.overflow = '';
            }
            function prev() { show(current > 0 ? current - 1 : images.length - 1); }
            function next() { show(current < images.length - 1 ? current + 1 : 0); }

            document.querySelectorAll('.gallery-thumb').forEach(function(el) {
                el.addEventListener('click', function() { show(parseInt(this.dataset.index)); });
            });
            document.getElementById('lb-close').addEventListener('click', hide);
            document.getElementById('lb-prev').addEventListener('click', prev);
            document.getElementById('lb-next').addEventListener('click', next);
            lb.addEventListener('click', function(e) { if (e.target === lb) hide(); });
            document.addEventListener('keydown', function(e) {
                if (lb.classList.contains('hidden')) return;
                if (e.key === 'Escape') hide();
                if (e.key === 'ArrowLeft') prev();
                if (e.key === 'ArrowRight') next();
            });
        })();
        </script>
        @endif

        {{-- Description --}}
        <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <h2 class="text-xl font-semibold mb-4">About This Project</h2>
                <div id="project-description" class="prose prose-invert prose-purple max-w-none
                    prose-headings:font-bold prose-headings:text-white
                    prose-p:text-gray-400 prose-p:leading-relaxed
                    prose-a:text-purple-400 prose-a:no-underline hover:prose-a:text-purple-300
                    prose-strong:text-white
                    prose-li:text-gray-400
                    prose-ul:list-disc prose-ol:list-decimal
                    prose-img:rounded-xl prose-img:border prose-img:border-white/10 prose-img:cursor-pointer prose-img:transition-transform prose-img:duration-300 hover:prose-img:scale-[1.02]">
                    {!! $project->description !!}
                </div>

                @if($project->process_steps && count($project->process_steps) > 0)
                <h3 class="text-lg font-semibold mt-8 mb-4">Process</h3>
                <div class="space-y-4">
                    @foreach($project->process_steps as $index => $step)
                    <div class="flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-lg bg-purple-500/20 border border-purple-500/20 flex items-center justify-center text-purple-400 text-sm font-bold shrink-0">
                            {{ $index + 1 }}</div>
                        <span class="text-gray-300 pt-1">{{ $step }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div>
                @if($project->software_used && count($project->software_used) > 0)
                <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                    <h3 class="font-semibold mb-4">Software Used</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->software_used as $sw)
                        <span class="text-sm bg-white/5 border border-white/10 text-gray-300 px-3 py-1.5 rounded-lg">{{
                            $sw }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Description Image Zoom Lightbox --}}
<div id="desc-lightbox" class="fixed inset-0 z-[9999] bg-black/95 backdrop-blur-xl items-center justify-center p-4 hidden cursor-zoom-out" onclick="this.classList.add('hidden');this.classList.remove('flex');document.body.style.overflow='';">
    <img id="desc-lightbox-img" src="" alt="Zoomed image" class="max-w-full max-h-[90vh] rounded-xl object-contain select-none">
    <button onclick="event.stopPropagation();document.getElementById('desc-lightbox').classList.add('hidden');document.getElementById('desc-lightbox').classList.remove('flex');document.body.style.overflow='';" class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors z-10">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
<script>
document.querySelectorAll('#project-description img').forEach(function(img) {
    img.style.cursor = 'zoom-in';
    img.addEventListener('click', function(e) {
        e.preventDefault();
        var lb = document.getElementById('desc-lightbox');
        document.getElementById('desc-lightbox-img').src = this.src;
        lb.classList.remove('hidden');
        lb.classList.add('flex');
        document.body.style.overflow = 'hidden';
    });
});
document.addEventListener('keydown', function(e) {
    var lb = document.getElementById('desc-lightbox');
    if (lb && !lb.classList.contains('hidden') && e.key === 'Escape') {
        lb.classList.add('hidden');
        lb.classList.remove('flex');
        document.body.style.overflow = '';
    }
});
</script>
@endsection