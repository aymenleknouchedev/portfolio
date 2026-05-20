@extends('layouts.admin')

@section('page_title', 'Projects')
@section('page_subtitle', $projects->count() . ' ' . Str::plural('project', $projects->count()))

@section('content')
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
    <p class="text-sm text-gray-400">Portfolio entries shown on the home page and portfolio.</p>
    <a href="{{ route('admin.projects.create') }}"
        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all">
        <i class="fa-solid fa-plus text-xs"></i> New Project
    </a>
</div>

<div x-data="{ tab: 'all' }" class="space-y-5">
    {{-- Tab pills --}}
    <div class="rounded-2xl bg-gray-900 border border-white/5 p-1.5 inline-flex gap-1">
        <button @click="tab = 'all'"
            :class="tab === 'all' ? 'bg-gradient-to-br from-purple-600 to-violet-700 text-white shadow-lg shadow-purple-500/30' : 'text-gray-400 hover:text-white hover:bg-white/5'"
            class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl transition-all">
            <i class="fa-solid fa-list text-xs"></i> All Projects
        </button>
        <button @click="tab = 'featured'"
            :class="tab === 'featured' ? 'bg-gradient-to-br from-purple-600 to-violet-700 text-white shadow-lg shadow-purple-500/30' : 'text-gray-400 hover:text-white hover:bg-white/5'"
            class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl transition-all">
            <i class="fa-solid fa-star text-xs"></i> Featured Order
        </button>
    </div>

    {{-- All Projects --}}
    <div x-show="tab === 'all'" x-transition class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[760px]">
                <thead>
                    <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">Title</th>
                        <th class="px-6 py-4 font-medium">Category</th>
                        <th class="px-6 py-4 font-medium">Featured</th>
                        <th class="px-6 py-4 font-medium">Published</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($projects as $project)
                    <tr class="transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-9 rounded-lg bg-gray-800/60 overflow-hidden shrink-0">
                                    @if($project->hero_image)
                                        <img src="{{ asset('storage/' . $project->hero_image) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-image text-gray-700 text-xs"></i></div>
                                    @endif
                                </div>
                                <span class="font-medium text-white truncate">{{ $project->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $project->projectCategory->name ?? $project->category }}</td>
                        <td class="px-6 py-4">
                            @if($project->is_featured)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    <i class="fa-solid fa-star text-[10px]"></i> Featured
                                </span>
                            @else
                                <span class="text-xs text-gray-600">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">
                            {{ $project->published_at?->format('M d, Y') ?? 'Draft' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.projects.download-images', $project) }}"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-emerald-400 hover:bg-emerald-500/10 transition-colors" title="Download all images as ZIP">
                                    <i class="fa-solid fa-download text-xs"></i>
                                </a>
                                <a href="{{ route('admin.projects.edit', $project) }}"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-purple-400 hover:bg-purple-500/10 transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Delete this project?')">
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
                                <i class="fa-solid fa-briefcase text-gray-600"></i>
                            </div>
                            <p class="text-gray-400 text-sm">No projects yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Featured Order --}}
    <div x-show="tab === 'featured'" x-transition class="rounded-2xl bg-gray-900 border border-white/5 p-5">
        <div class="mb-4 flex items-center gap-3 p-3 rounded-xl bg-purple-500/5 border border-purple-500/20">
            <i class="fa-solid fa-circle-info text-purple-400"></i>
            <p class="text-sm text-gray-300">Drag and drop to reorder how featured projects appear on the homepage.</p>
        </div>
        <div id="featured-sortable" class="space-y-2">
            @forelse($featuredProjects as $project)
            <div class="sortable-item flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-purple-500/30 cursor-grab active:cursor-grabbing transition-all" data-id="{{ $project->id }}">
                <div class="text-gray-500 hover:text-purple-400 shrink-0 drag-handle">
                    <i class="fa-solid fa-grip-vertical"></i>
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
                <div class="text-xs text-purple-400 font-mono shrink-0 font-semibold">#{{ $loop->iteration }}</div>
            </div>
            @empty
            <div class="p-10 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-star text-gray-600"></i>
                </div>
                <p class="text-gray-400 text-sm">No featured projects. Mark a project as "Featured" to order it here.</p>
            </div>
            @endforelse
        </div>
        @if($featuredProjects->count())
        <div id="sort-status" class="mt-3 text-sm text-emerald-400 hidden flex items-center gap-1.5">
            <i class="fa-solid fa-circle-check"></i> Order saved
        </div>
        @endif
    </div>
</div>

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
                    status.classList.add('flex');
                    setTimeout(function() { status.classList.add('hidden'); status.classList.remove('flex'); }, 2000);
                }
            });
        }
    });
});
</script>
@endsection
