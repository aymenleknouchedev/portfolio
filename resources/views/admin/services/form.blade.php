@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">{{ isset($service) ? 'Edit Service' : 'New Service' }}</h1>
<form action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6">
    @csrf
    @if(isset($service)) @method('PUT') @endif
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
        <input type="text" name="title" value="{{ old('title', $service->title ?? '') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
        <textarea name="description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('description', $service->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">WhatsApp Number</label>
        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $service->whatsapp_number ?? '') }}" placeholder="Optional, uses global default" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Example Image</label>
        <input type="file" name="example_image" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none file:bg-purple-600 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-1 file:mr-4">
    </div>
    <div>
        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded bg-white/5 border-white/10 text-purple-600 focus:ring-purple-500">
            <span class="text-sm text-gray-300">Active</span>
        </label>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">{{ isset($service) ? 'Update' : 'Create' }} Service</button>
        <a href="{{ route('admin.services.index') }}" class="bg-white/5 hover:bg-white/10 text-gray-300 font-medium px-6 py-3 rounded-xl transition-all">Cancel</a>
    </div>
</form>
@endsection
