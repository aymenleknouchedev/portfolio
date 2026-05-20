@extends('layouts.admin')

@section('page_title', 'Purchases')
@section('page_subtitle', $purchases->total() . ' order' . ($purchases->total() !== 1 ? 's' : '') . ' total')

@section('content')
@php
    $totalRevenue = \App\Models\Purchase::where('status', 'completed')->sum('amount');
    $completedCount = \App\Models\Purchase::where('status', 'completed')->count();
    $pendingCount = \App\Models\Purchase::where('status', 'pending')->count();
    $failedCount = \App\Models\Purchase::where('status', 'failed')->count();
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="rounded-xl bg-gray-900/60 border border-white/5 p-4">
        <div class="text-xs text-gray-500 uppercase tracking-wider font-medium">Revenue</div>
        <div class="text-2xl font-bold mt-1 text-emerald-400">${{ number_format($totalRevenue, 2) }}</div>
    </div>
    <div class="rounded-xl bg-gray-900/60 border border-white/5 p-4">
        <div class="text-xs text-gray-500 uppercase tracking-wider font-medium">Completed</div>
        <div class="text-2xl font-bold mt-1">{{ number_format($completedCount) }}</div>
    </div>
    <div class="rounded-xl bg-gray-900/60 border border-white/5 p-4">
        <div class="text-xs text-gray-500 uppercase tracking-wider font-medium">Pending</div>
        <div class="text-2xl font-bold mt-1 text-amber-400">{{ number_format($pendingCount) }}</div>
    </div>
    <div class="rounded-xl bg-gray-900/60 border border-white/5 p-4">
        <div class="text-xs text-gray-500 uppercase tracking-wider font-medium">Failed</div>
        <div class="text-2xl font-bold mt-1 text-red-400">{{ number_format($failedCount) }}</div>
    </div>
</div>

<div class="rounded-2xl bg-gray-900/60 border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[820px]">
            <thead class="bg-white/[0.02]">
                <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Customer</th>
                    <th class="px-6 py-4 font-medium">Add-on</th>
                    <th class="px-6 py-4 font-medium">Amount</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium">Order ID</th>
                    <th class="px-6 py-4 font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($purchases as $purchase)
                <tr class="hover:bg-white/[0.03] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500/40 to-violet-600/40 flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($purchase->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium truncate">{{ $purchase->user->name ?? '—' }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $purchase->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ $purchase->addon->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-semibold">${{ number_format($purchase->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $sc = match($purchase->status) {
                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                'failed' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                default => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium border {{ $sc }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ Str::limit($purchase->paypal_order_id ?? $purchase->download_token ?? '—', 18) }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $purchase->created_at->format('M d, Y · H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-credit-card text-gray-600"></i>
                        </div>
                        <p class="text-gray-400 text-sm">No purchases yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($purchases->hasPages())
    <div class="p-4 border-t border-white/5">
        {{ $purchases->links() }}
    </div>
    @endif
</div>
@endsection
