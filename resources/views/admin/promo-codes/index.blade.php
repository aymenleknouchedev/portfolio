@extends('layouts.admin')

@section('page_title', 'Promo Codes')
@section('page_subtitle', $promoCodes->count() . ' ' . Str::plural('code', $promoCodes->count()))

@section('content')
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
    <p class="text-sm text-gray-400">Create discount codes for checkout.</p>
    <a href="{{ route('admin.promo-codes.create') }}"
        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all">
        <i class="fa-solid fa-plus text-xs"></i> New Promo Code
    </a>
</div>

<div class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[760px]">
            <thead>
                <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Code</th>
                    <th class="px-6 py-4 font-medium">Discount</th>
                    <th class="px-6 py-4 font-medium">Usage</th>
                    <th class="px-6 py-4 font-medium">Expires</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($promoCodes as $promo)
                <tr class="transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg border border-amber-500/20 flex items-center justify-center">
                                <i class="fa-solid fa-ticket text-amber-300 text-sm"></i>
                            </div>
                            <span class="font-mono font-semibold text-purple-300 tracking-wider">{{ $promo->code }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        @if($promo->type === 'percentage')
                            <span class="text-emerald-400">{{ $promo->value }}%</span>
                            <span class="text-xs text-gray-500 font-normal">off</span>
                        @else
                            <span class="text-emerald-400">${{ number_format($promo->value, 2) }}</span>
                            <span class="text-xs text-gray-500 font-normal">off</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-300">
                        <span class="font-semibold">{{ $promo->used_count }}</span>
                        @if($promo->max_uses)
                            <span class="text-gray-500"> / {{ $promo->max_uses }}</span>
                        @else
                            <span class="text-gray-500 text-xs ml-1">/ ∞</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $promo->expires_at ? $promo->expires_at->format('M d, Y') : 'Never' }}</td>
                    <td class="px-6 py-4">
                        @if($promo->isValid())
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('admin.promo-codes.edit', $promo) }}"
                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-purple-400 hover:bg-purple-500/10 transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.promo-codes.destroy', $promo) }}" method="POST" class="inline" onsubmit="return confirm('Delete this promo code?')">
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
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-ticket text-gray-600"></i>
                        </div>
                        <p class="text-gray-400 text-sm">No promo codes yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
