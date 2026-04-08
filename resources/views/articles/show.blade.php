@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8" data-aos="fade-up">
            <a href="{{ route('learn.index') }}"
                class="text-sm text-gray-400 hover:text-purple-400 transition-colors inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Articles
            </a>
        </div>

        <article data-aos="fade-up">
            @if($article->published_at)
            <div class="flex items-center gap-4">
                <time class="text-sm text-gray-500">{{ $article->published_at->format('F j, Y') }}</time>
                <span class="text-sm text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {{ number_format(90 + $article->views_count) }} views
                </span>
            </div>
            @endif
            <h1 class="text-3xl lg:text-5xl font-bold mt-2 mb-6">{{ $article->title }}</h1>

            @if($article->hero_image)
            <div class="aspect-video rounded-2xl overflow-hidden mb-8 bg-gray-900">
                <img src="{{ asset('storage/' . $article->hero_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
            </div>
            @endif

            {{-- YouTube Video Embed --}}
            @if($article->youtube_url)
            @php
                $youtubeId = null;
                if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]{11})/', $article->youtube_url, $matches)) {
                    $youtubeId = $matches[1];
                }
            @endphp
            @if($youtubeId)
            <div class="mb-8 rounded-2xl overflow-hidden border border-white/5">
                <div class="aspect-video">
                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" class="w-full h-full"
                        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>
            @endif
            @endif

            <div
                class="prose prose-invert prose-purple max-w-none
                prose-headings:font-bold prose-headings:text-white
                prose-p:text-gray-400 prose-p:leading-relaxed
                prose-a:text-purple-400 prose-a:no-underline hover:prose-a:text-purple-300
                prose-strong:text-white
                prose-li:text-gray-400
                prose-ul:list-disc prose-ol:list-decimal
                prose-code:bg-white/5 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-purple-300">
                {!! $article->content ?? '' !!}
            </div>
        </article>
    </div>
</div>
@endsection