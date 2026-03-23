@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Add-ons</h1>
    <a href="{{ route('admin.addons.create') }}" class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-all">+ New Add-on</a>
</div>
<div class="rounded-xl bg-gray-900 border border-white/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-gray-400 border-b border-white/5"><th class="text-left p-4">Name</th><th class="text-left p-4">Category</th><th class="text-left p-4">Price</th><th class="text-left p-4">Featured</th><th class="text-right p-4">Actions</th></tr></thead>
        <tbody>
            @foreach($addons as $addon)
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="p-4 font-medium">{{ $addon->name }}</td>
                <td class="p-4 text-gray-400">{{ $addon->category->name }}</td>
                <td class="p-4">
                    @if($addon->price <= 0)
                        <span class="text-green-400 font-medium">Free</span>
                        @if(!$addon->file_path)
                            <span class="ml-1 text-red-400 text-xs" title="No downloadable file or URL set">⚠ no file</span>
                        @endif
                    @else
                        ${{ number_format($addon->price, 2) }}
                    @endif
                </td>
                <td class="p-4">@if($addon->is_featured) <span class="text-amber-400">★</span> @endif</td>
                <td class="p-4 text-right">
                    <a href="{{ route('admin.addons.edit', $addon) }}" class="text-purple-400 hover:text-purple-300 mr-3">Edit</a>
                    <form action="{{ route('admin.addons.destroy', $addon) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($addons->isEmpty()) <p class="p-8 text-center text-gray-400">No add-ons yet.</p> @endif
</div>
@endsection
