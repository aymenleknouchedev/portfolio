@extends('layouts.admin')

@section('page_title', isset($service) ? 'Edit Service' : 'New Service')
@section('page_subtitle', isset($service) ? $service->title : 'Create a service offering')

@section('content')
<form action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if(isset($service)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-5">
            <header class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl section-header-icon flex items-center justify-center">
                    <i class="fa-solid fa-concierge-bell text-teal-300 text-sm"></i>
                </div>
                <div>
                    <h2 class="font-semibold">Service Details</h2>
                    <p class="text-xs text-gray-500">Listed on the Services page.</p>
                </div>
            </header>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Title</label>
                <input type="text" name="title" value="{{ old('title', $service->title ?? '') }}" required
                    placeholder="e.g. VFX Compositing"
                    class="w-full rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Description</label>
                <textarea name="description" rows="5" placeholder="What's included in this service…"
                    class="w-full rounded-xl px-4 py-3 resize-none">{{ old('description', $service->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">WhatsApp Number <span class="text-gray-600 normal-case">— optional, falls back to global default</span></label>
                <div class="relative">
                    <i class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400 text-sm pointer-events-none"></i>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $service->whatsapp_number ?? '') }}"
                        placeholder="+1 234 567 890"
                        class="w-full rounded-xl pl-10 pr-4 py-3">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Example Image</label>
                @if(isset($service) && $service->example_image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $service->example_image) }}" alt="" class="w-full max-h-48 object-cover rounded-xl border border-white/10">
                </div>
                @endif
                <input type="file" name="example_image" accept="image/*"
                    class="w-full rounded-xl px-4 py-3 text-sm">
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-2xl bg-gradient-to-br from-purple-600/15 via-violet-700/10 to-gray-900 border border-purple-500/20 p-6">
                <h3 class="font-semibold mb-1">{{ isset($service) ? 'Save Changes' : 'Create Service' }}</h3>
                <p class="text-xs text-gray-400 mb-4">Visible after saving.</p>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold py-3 rounded-xl transition-all">
                    <i class="fa-solid fa-{{ isset($service) ? 'floppy-disk' : 'plus' }}"></i>
                    {{ isset($service) ? 'Update Service' : 'Create Service' }}
                </button>
                <a href="{{ route('admin.services.index') }}"
                    class="block w-full text-center mt-2 bg-white/5 hover:bg-white/10 text-gray-300 font-medium py-3 rounded-xl transition-all">
                    Cancel
                </a>
            </div>

            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <h3 class="font-semibold mb-3 text-sm">Visibility</h3>
                <label class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 cursor-pointer transition-colors">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded bg-gray-950 border-white/10 text-purple-600 focus:ring-purple-500">
                    <div>
                        <div class="text-sm font-medium">Active</div>
                        <div class="text-[11px] text-gray-500">Shown on the Services page.</div>
                    </div>
                </label>
            </div>
        </aside>
    </div>
</form>
@endsection
