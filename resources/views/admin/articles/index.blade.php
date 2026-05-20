@extends('layouts.admin')

@section('page_title', 'Articles')
@section('page_subtitle', $articles->count() . ' ' . Str::plural('article', $articles->count()))

@section('content')
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
    <p class="text-sm text-gray-400">Blog posts and tutorials shown on the "Learn" page.</p>
    <a href="{{ route('admin.articles.create') }}"
        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all">
        <i class="fa-solid fa-plus text-xs"></i> New Article
    </a>
</div>

<div class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead>
                <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Title</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium">Published</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($articles as $article)
                <tr class="transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg border border-sky-500/20 flex items-center justify-center">
                                <i class="fa-solid fa-newspaper text-sky-300 text-sm"></i>
                            </div>
                            <span class="font-medium text-white">{{ $article->title }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($article->is_published)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Published
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $article->published_at?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('admin.articles.edit', $article) }}"
                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-purple-400 hover:bg-purple-500/10 transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Delete this article?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-red-400 hover:bg-red-500/10 transition-colors" title="Delete">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-newspaper text-gray-600"></i>
                        </div>
                        <p class="text-gray-400 text-sm">No articles yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
