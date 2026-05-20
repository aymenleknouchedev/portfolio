@extends('layouts.admin')

@section('page_title', isset($brand) ? 'Edit Brand' : 'New Brand')
@section('page_subtitle', isset($brand) ? $brand->name : 'Add a new brand logo')

@section('content')
<form action="{{ isset($brand) ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if(isset($brand)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-5">
            <header class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl section-header-icon flex items-center justify-center">
                    <i class="fa-solid fa-handshake text-purple-300 text-sm"></i>
                </div>
                <div>
                    <h2 class="font-semibold">Brand Details</h2>
                    <p class="text-xs text-gray-500">Brand logos shown on the homepage strip.</p>
                </div>
            </header>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Name *</label>
                <input type="text" name="name" value="{{ old('name', $brand->name ?? '') }}" required
                    placeholder="e.g. Acme Studios"
                    class="w-full rounded-xl px-4 py-3">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Logo *</label>
                @if(isset($brand) && $brand->logo)
                <div class="mb-3 p-4 rounded-xl bg-white/5 border border-white/10 inline-block">
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-12 w-auto object-contain">
                </div>
                @endif
                <input type="file" name="logo" accept="image/*"
                    class="w-full rounded-xl px-4 py-3 text-sm"
                    {{ isset($brand) ? '' : 'required' }}>
                <p class="text-xs text-gray-500 mt-2">PNG or SVG with transparent background recommended. Max 2MB.</p>
                @error('logo') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Website URL</label>
                    <div class="relative">
                        <i class="fa-solid fa-link absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm pointer-events-none"></i>
                        <input type="url" name="url" value="{{ old('url', $brand->url ?? '') }}" placeholder="https://example.com"
                            class="w-full rounded-xl pl-10 pr-4 py-3">
                    </div>
                    @error('url') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $brand->sort_order ?? 0) }}"
                        class="w-full rounded-xl px-4 py-3">
                    @error('sort_order') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-2xl bg-gradient-to-br from-purple-600/15 via-violet-700/10 to-gray-900 border border-purple-500/20 p-6">
                <h3 class="font-semibold mb-1">{{ isset($brand) ? 'Save Changes' : 'Create Brand' }}</h3>
                <p class="text-xs text-gray-400 mb-4">Required fields are marked with *.</p>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold py-3 rounded-xl transition-all">
                    <i class="fa-solid fa-{{ isset($brand) ? 'floppy-disk' : 'plus' }}"></i>
                    {{ isset($brand) ? 'Update Brand' : 'Create Brand' }}
                </button>
                <a href="{{ route('admin.brands.index') }}"
                    class="block w-full text-center mt-2 bg-white/5 hover:bg-white/10 text-gray-300 font-medium py-3 rounded-xl transition-all">
                    Cancel
                </a>
            </div>

            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <h3 class="font-semibold mb-3 text-sm">Visibility</h3>
                <label class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 cursor-pointer transition-colors">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                        {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded bg-gray-950 border-white/10 text-purple-600 focus:ring-purple-500">
                    <div>
                        <div class="text-sm font-medium">Active</div>
                        <div class="text-[11px] text-gray-500">Shown in the homepage brand strip.</div>
                    </div>
                </label>
            </div>
        </aside>
    </div>
</form>
@endsection
