@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Projects</h1>
    <a href="{{ route('admin.projects.create') }}" class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-all">+ New Project</a>
</div>

{{-- Tabs --}}
<div x-data="{ tab: 'all' }" class="space-y-4">
    <div class="flex gap-2 border-b border-white/5 pb-0">
        <button @click="tab = 'all'" :class="tab === 'all' ? 'border-purple-500 text-white' : 'border-transparent text-gray-400 hover:text-gray-300'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-all -mb-px">All Projects</button>
        <button @click="tab = 'featured'" :class="tab === 'featured' ? 'border-purple-500 text-white' : 'border-transparent text-gray-400 hover:text-gray-300'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition-all -mb-px">Featured Order</button>
    </div>

    {{-- All Projects Tab --}}
    <div x-show="tab === 'all'" x-transition>
        <div class="rounded-xl bg-gray-900 border border-white/5 overflow-hidden">
            <table class="w-full text-sm">
                <thead><tr class="text-gray-400 border-b border-white/5"><th class="text-left p-4">Title</th><th class="text-left p-4">Category</th><th class="text-left p-4">Featured</th><th class="text-left p-4">Published</th><th class="text-right p-4">Actions</th></tr></thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="p-4 font-medium">{{ $project->title }}</td>
                        <td class="p-4 text-gray-400">{{ $project->projectCategory->name ?? $project->category }}</td>
                        <td class="p-4">
                            @if($project->is_featured)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-500/20 text-purple-400">Featured</span>
                            @else
                            <span class="text-xs text-gray-600">—</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-400">{{ $project->published_at?->format('M d, Y') ?? 'Draft' }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="text-purple-400 hover:text-purple-300 mr-3">Edit</a>
                            <a href="{{ route('admin.projects.download-images', $project) }}" class="text-green-400 hover:text-green-300 mr-3" title="Download all project images as ZIP">&#8659; Images</a>
                            <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300">Delete</button></form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($projects->isEmpty()) <p class="p-8 text-center text-gray-400">No projects yet.</p> @endif
        </div>
    </div>

    {{-- Featured Order Tab (Drag & Drop) --}}
    <div x-show="tab === 'featured'" x-transition>
        <p class="text-sm text-gray-400 mb-4">Drag and drop to reorder how featured projects appear on the homepage.</p>
        <div id="featured-sortable" class="space-y-2">
            @forelse($featuredProjects as $project)
            <div class="sortable-item flex items-center gap-4 p-4 rounded-xl bg-gray-900 border border-white/5 hover:border-purple-500/20 cursor-grab active:cursor-grabbing transition-all" data-id="{{ $project->id }}">
                <div class="text-gray-600 shrink-0 drag-handle">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                </div>
                <div class="w-16 h-10 rounded-lg bg-gray-800 overflow-hidden shrink-0">
                    @if($project->hero_image)
                    <img src="{{ asset('storage/' . $project->hero_image) }}" alt="" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-sm truncate">{{ $project->title }}</div>
                    <div class="text-xs text-gray-500">{{ $project->projectCategory->name ?? $project->category }}</div>
                </div>
                <div class="text-xs text-gray-600 font-mono shrink-0">#{{ $loop->iteration }}</div>
            </div>
            @empty
            <p class="p-8 text-center text-gray-400">No featured projects. Mark projects as "Featured" to order them here.</p>
            @endforelse
        </div>
        @if($featuredProjects->count())
        <div id="sort-status" class="mt-3 text-sm text-gray-500 hidden">
            <span class="text-green-400">✓</span> Order saved
        </div>
        @endif
    </div>
</div>

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById('featured-sortable');
    if (!el || !el.children.length) return;

    Sortable.create(el, {
        animation: 200,
        handle: '.drag-handle',
        ghostClass: 'opacity-30',
        onEnd: function() {
            var order = Array.from(el.querySelectorAll('.sortable-item')).map(function(item) {
                return parseInt(item.dataset.id);
            });

            // Update position numbers
            el.querySelectorAll('.sortable-item').forEach(function(item, i) {
                item.querySelector('.font-mono').textContent = '#' + (i + 1);
            });

            fetch('{{ route("admin.projects.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: order })
            }).then(function(res) {
                if (res.ok) {
                    var status = document.getElementById('sort-status');
                    status.classList.remove('hidden');
                    setTimeout(function() { status.classList.add('hidden'); }, 2000);
                }
            });
        }
    });
});
</script>
@endsection
