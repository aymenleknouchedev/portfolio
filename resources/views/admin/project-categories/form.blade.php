@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">{{ isset($category) ? 'Edit Project Category' : 'New Project Category' }}</h1>

<form action="{{ isset($category) ? route('admin.project-categories.update', $category) : route('admin.project-categories.store') }}" method="POST" class="max-w-2xl space-y-6">
    @csrf
    @if(isset($category)) @method('PUT') @endif

    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
        @error('name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
        <textarea name="description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded bg-white/5 border-white/10 text-purple-600 focus:ring-purple-500">
            <span class="text-sm text-gray-300">Active</span>
        </label>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">{{ isset($category) ? 'Update' : 'Create' }} Category</button>
        <a href="{{ route('admin.project-categories.index') }}" class="bg-white/5 hover:bg-white/10 text-gray-300 font-medium px-6 py-3 rounded-xl transition-all">Cancel</a>
    </div>
</form>
@endsection
