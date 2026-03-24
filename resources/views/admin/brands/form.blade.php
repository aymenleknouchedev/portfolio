@extends('layouts.admin')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.brands.index') }}" class="text-gray-400 hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">{{ isset($brand) ? 'Edit Brand' : 'New Brand' }}</h1>
    </div>

    <form action="{{ isset($brand) ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($brand)) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Name *</label>
            <input type="text" name="name" value="{{ old('name', $brand->name ?? '') }}" required
                class="w-full bg-gray-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors">
            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Logo *</label>
            @if(isset($brand) && $brand->logo)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-12 w-auto object-contain bg-white/10 rounded px-3 py-2">
                </div>
            @endif
            <input type="file" name="logo" accept="image/*"
                class="w-full bg-gray-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:bg-purple-600 file:text-white hover:file:bg-purple-500"
                {{ isset($brand) ? '' : 'required' }}>
            <p class="text-gray-500 text-xs mt-1">Recommended: PNG or SVG with transparent background. Max 2MB.</p>
            @error('logo') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Website URL</label>
            <input type="url" name="url" value="{{ old('url', $brand->url ?? '') }}" placeholder="https://example.com"
                class="w-full bg-gray-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors">
            @error('url') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $brand->sort_order ?? 0) }}"
                class="w-full bg-gray-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors">
            @error('sort_order') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1" id="is_active"
                {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }}
                class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-purple-600 focus:ring-purple-500">
            <label for="is_active" class="text-sm text-gray-300">Active</label>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-lg transition-all">
                {{ isset($brand) ? 'Update Brand' : 'Create Brand' }}
            </button>
            <a href="{{ route('admin.brands.index') }}" class="text-gray-400 hover:text-white transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
