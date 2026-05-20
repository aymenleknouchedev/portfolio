@extends('layouts.admin')

@section('page_title', isset($category) ? 'Edit Category' : 'New Category')
@section('page_subtitle', isset($category) ? $category->name : 'Create a new add-on category')

@section('content')
<form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="space-y-6">
    @csrf
    @if(isset($category)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-5">
            <header class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl section-header-icon flex items-center justify-center">
                    <i class="fa-solid fa-tag text-purple-300 text-sm"></i>
                </div>
                <div>
                    <h2 class="font-semibold">Category Details</h2>
                    <p class="text-xs text-gray-500">Used to group add-ons in the shop.</p>
                </div>
            </header>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Name</label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                    placeholder="e.g. Particle Systems"
                    class="w-full rounded-xl px-4 py-3">
                @error('name') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Description</label>
                <textarea name="description" rows="4"
                    placeholder="Short description shown on category pages…"
                    class="w-full rounded-xl px-4 py-3 resize-none">{{ old('description', $category->description ?? '') }}</textarea>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="card-accent p-6">
                <h3 class="font-semibold mb-1">{{ isset($category) ? 'Save Changes' : 'Create Category' }}</h3>
                <p class="text-xs text-gray-400 mb-4">All set? Click below to publish.</p>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold py-3 rounded-xl transition-all">
                    <i class="fa-solid fa-{{ isset($category) ? 'floppy-disk' : 'plus' }}"></i>
                    {{ isset($category) ? 'Save Changes' : 'Create Category' }}
                </button>
                <a href="{{ route('admin.categories.index') }}"
                    class="block w-full text-center mt-2 bg-white/5 hover:bg-white/10 text-gray-300 font-medium py-3 rounded-xl transition-all">
                    Cancel
                </a>
            </div>

            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <h3 class="font-semibold mb-3 text-sm">Visibility</h3>
                <label class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 cursor-pointer transition-colors">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded bg-gray-950 border-white/10 text-purple-600 focus:ring-purple-500">
                    <div>
                        <div class="text-sm font-medium">Active</div>
                        <div class="text-[11px] text-gray-500">Visible to clients on the shop.</div>
                    </div>
                </label>
            </div>
        </aside>
    </div>
</form>
@endsection
