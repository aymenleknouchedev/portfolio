@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-all">+ New Category</a>
</div>

<div class="rounded-xl bg-gray-900 border border-white/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-gray-400 border-b border-white/5"><th class="text-left p-4">Name</th><th class="text-left p-4">Slug</th><th class="text-left p-4">Add-ons</th><th class="text-left p-4">Status</th><th class="text-right p-4">Actions</th></tr></thead>
        <tbody>
            @foreach($categories as $category)
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="p-4 font-medium">{{ $category->name }}</td>
                <td class="p-4 text-gray-400">{{ $category->slug }}</td>
                <td class="p-4">{{ $category->addons_count }}</td>
                <td class="p-4"><span class="px-2 py-1 rounded-full text-xs {{ $category->is_active ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td class="p-4 text-right">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-purple-400 hover:text-purple-300 mr-3">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($categories->isEmpty()) <p class="p-8 text-center text-gray-400">No categories yet.</p> @endif
</div>
@endsection
