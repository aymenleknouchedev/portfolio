@extends('layouts.admin')

@section('page_title', 'Add-ons')
@section('page_subtitle', $addons->count() . ' product' . ($addons->count() !== 1 ? 's' : ''))

@section('content')
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
    <p class="text-sm text-gray-400">Manage your digital products and license packs.</p>
    <a href="{{ route('admin.addons.create') }}"
        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all hover: hover:">
        <i class="fa-solid fa-plus text-xs"></i>
        New Add-on
    </a>
</div>

<div class="rounded-2xl bg-gray-900/60 border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[720px]">
            <thead class="bg-white/[0.02]">
                <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Add-on</th>
                    <th class="px-6 py-4 font-medium">Category</th>
                    <th class="px-6 py-4 font-medium">Price</th>
                    <th class="px-6 py-4 font-medium">Packs</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($addons as $addon)
                <tr class="hover:bg-white/[0.03] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($addon->cover_image)
                                <img src="{{ asset('storage/' . $addon->cover_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover border border-white/10 shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-puzzle-piece text-purple-300 text-sm"></i>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <div class="font-medium text-white truncate">{{ $addon->name }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ Str::limit(strip_tags($addon->description ?? ''), 60) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400">{{ $addon->category->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($addon->price <= 0)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 text-xs font-semibold border border-emerald-500/20">Free</span>
                            @if(!$addon->file_path)
                                <span class="ml-1 text-red-400 text-xs" title="No downloadable file or URL set">
                                    <i class="fa-solid fa-triangle-exclamation"></i> no file
                                </span>
                            @endif
                        @else
                            <span class="font-semibold">${{ number_format($addon->price, 2) }}</span>
                            @if($addon->original_price && $addon->original_price > $addon->price)
                                <span class="ml-1 text-xs text-gray-500 line-through">${{ number_format($addon->original_price, 2) }}</span>
                            @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400">
                        @php $tierCount = $addon->license_tiers ? count($addon->license_tiers) : 0; @endphp
                        @if($tierCount > 0)
                            <span class="inline-flex items-center gap-1 text-xs">
                                <i class="fa-solid fa-layer-group text-purple-400"></i>
                                {{ $tierCount }} {{ Str::plural('pack', $tierCount) }}
                            </span>
                        @else
                            <span class="text-xs text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($addon->is_featured)
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                <i class="fa-solid fa-star text-[10px]"></i> Featured
                            </span>
                        @else
                            <span class="text-xs text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('shop.show', $addon->slug) }}" target="_blank"
                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors" title="View on site">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                            <a href="{{ route('admin.addons.edit', $addon) }}"
                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-purple-400 hover:bg-purple-500/10 transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.addons.destroy', $addon) }}" method="POST" class="inline" onsubmit="return confirm('Delete this add-on?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-red-400 hover:bg-red-500/10 transition-colors" title="Delete">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-puzzle-piece text-gray-600"></i>
                        </div>
                        <p class="text-gray-400 text-sm">No add-ons yet.</p>
                        <a href="{{ route('admin.addons.create') }}" class="inline-flex items-center gap-2 mt-4 text-purple-400 hover:text-purple-300 text-sm">
                            <i class="fa-solid fa-plus text-xs"></i> Create your first add-on
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
