@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Promo Codes</h1>
    <a href="{{ route('admin.promo-codes.create') }}" class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-all">+ New Promo Code</a>
</div>
<div class="rounded-xl bg-gray-900 border border-white/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-gray-400 border-b border-white/5"><th class="text-left p-4">Code</th><th class="text-left p-4">Discount</th><th class="text-left p-4">Used</th><th class="text-left p-4">Expires</th><th class="text-left p-4">Status</th><th class="text-right p-4">Actions</th></tr></thead>
        <tbody>
            @foreach($promoCodes as $promo)
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="p-4 font-mono font-semibold text-purple-400">{{ $promo->code }}</td>
                <td class="p-4">
                    @if($promo->type === 'percentage')
                        {{ $promo->value }}% off
                    @else
                        ${{ number_format($promo->value, 2) }} off
                    @endif
                </td>
                <td class="p-4 text-gray-400">{{ $promo->used_count }}{{ $promo->max_uses ? ' / ' . $promo->max_uses : '' }}</td>
                <td class="p-4 text-gray-400">{{ $promo->expires_at ? $promo->expires_at->format('M j, Y') : '—' }}</td>
                <td class="p-4">
                    @if($promo->isValid())
                        <span class="text-green-400 text-xs font-medium bg-green-500/10 px-2 py-1 rounded-full">Active</span>
                    @else
                        <span class="text-red-400 text-xs font-medium bg-red-500/10 px-2 py-1 rounded-full">Inactive</span>
                    @endif
                </td>
                <td class="p-4 text-right">
                    <a href="{{ route('admin.promo-codes.edit', $promo) }}" class="text-purple-400 hover:text-purple-300 mr-3">Edit</a>
                    <form action="{{ route('admin.promo-codes.destroy', $promo) }}" method="POST" class="inline" onsubmit="return confirm('Delete this promo code?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($promoCodes->isEmpty()) <p class="p-8 text-center text-gray-400">No promo codes yet.</p> @endif
</div>
@endsection
