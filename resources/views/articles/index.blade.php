@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">Learn</span>
            <h1 class="text-4xl lg:text-6xl font-bold mt-2">Articles & Tutorials</h1>
        </div>

        {{-- Course Waitlist --}}
        <div class="mb-16 rounded-2xl bg-gradient-to-r from-purple-900/30 to-violet-900/20 border border-purple-500/20 p-8 text-center" data-aos="fade-up">
            <h3 class="text-xl font-bold mb-2">🚀 Courses Coming Soon!</h3>
            <p class="text-gray-400 mb-6">Join the waitlist to get early access to our premium courses.</p>
            <form action="{{ route('waitlist.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                @csrf
                <input type="hidden" name="course_name" value="General Waitlist">
                <input type="email" name="email" placeholder="Enter your email" required class="flex-1 bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none">
                <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">Join Waitlist</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($articles as $article)
            <a href="{{ route('learn.show', $article->slug) }}" class="group rounded-2xl overflow-hidden bg-gray-900 border border-white/5 hover:border-purple-500/20 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="{{ $loop->index % 3 * 100 }}">
                <div class="aspect-video bg-gradient-to-br from-purple-900/20 to-gray-800">
                    @if($article->hero_image)
                        <img src="{{ asset('storage/' . $article->hero_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <time class="text-xs text-gray-500">{{ $article->published_at?->format('M d, Y') }}</time>
                    <h3 class="text-lg font-semibold mt-2 group-hover:text-purple-400 transition-colors">{{ $article->title }}</h3>
                    <p class="text-gray-400 text-sm mt-2 line-clamp-2">{{ $article->excerpt }}</p>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
