@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Articles</h1>
    <a href="{{ route('admin.articles.create') }}" class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-all">+ New Article</a>
</div>
<div class="rounded-xl bg-gray-900 border border-white/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-gray-400 border-b border-white/5"><th class="text-left p-4">Title</th><th class="text-left p-4">Status</th><th class="text-left p-4">Published</th><th class="text-right p-4">Actions</th></tr></thead>
        <tbody>
            @foreach($articles as $article)
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="p-4 font-medium">{{ $article->title }}</td>
                <td class="p-4"><span class="px-2 py-1 rounded-full text-xs {{ $article->is_published ? 'bg-green-500/20 text-green-400' : 'bg-amber-500/20 text-amber-400' }}">{{ $article->is_published ? 'Published' : 'Draft' }}</span></td>
                <td class="p-4 text-gray-400">{{ $article->published_at?->format('M d, Y') ?? '—' }}</td>
                <td class="p-4 text-right">
                    <a href="{{ route('admin.articles.edit', $article) }}" class="text-purple-400 hover:text-purple-300 mr-3">Edit</a>
                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($articles->isEmpty()) <p class="p-8 text-center text-gray-400">No articles yet.</p> @endif
</div>
@endsection
