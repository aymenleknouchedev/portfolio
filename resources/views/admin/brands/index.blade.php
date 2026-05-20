@extends('layouts.admin')

@section('page_title', 'Brands')
@section('page_subtitle', $brands->count() . ' ' . Str::plural('brand', $brands->count()))

@section('content')
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
    <p class="text-sm text-gray-400">Manage brand logos shown on the homepage.</p>
    <a href="{{ route('admin.brands.create') }}"
        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all">
        <i class="fa-solid fa-plus text-xs"></i> New Brand
    </a>
</div>

<div class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead>
                <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Logo</th>
                    <th class="px-6 py-4 font-medium">Name</th>
                    <th class="px-6 py-4 font-medium">Order</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($brands as $brand)
                <tr class="transition-colors">
                    <td class="px-6 py-4">
                        <div class="h-12 w-24 rounded-lg bg-white/5 border border-white/10 px-3 py-2 flex items-center justify-center">
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-7 w-auto max-w-full object-contain">
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-white">{{ $brand->name }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-semibold bg-white/5 border border-white/10">#{{ $brand->sort_order }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($brand->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-500/10 text-gray-400 border border-gray-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('admin.brands.edit', $brand) }}"
                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-purple-400 hover:bg-purple-500/10 transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="inline" onsubmit="return confirm('Delete this brand?')">
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
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-handshake text-gray-600"></i>
                        </div>
                        <p class="text-gray-400 text-sm">No brands yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
