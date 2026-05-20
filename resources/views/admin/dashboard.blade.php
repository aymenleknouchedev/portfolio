@extends('layouts.admin')

@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome back, ' . auth()->user()->name)

@section('content')
@php
    $prevMonth = \App\Models\Purchase::where('status', 'completed')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->sum('amount');
    $monthGrowth = $prevMonth > 0 ? round((($stats['monthly_revenue'] - $prevMonth) / $prevMonth) * 100, 1) : ($stats['monthly_revenue'] > 0 ? 100 : 0);
@endphp

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    {{-- Revenue --}}
    <div class="card-accent p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-purple-300"></i>
            </div>
            <span class="text-xs px-2 py-1 rounded-md font-medium {{ $monthGrowth >= 0 ? 'bg-emerald-500/15 text-emerald-300' : 'bg-red-500/15 text-red-300' }}">
                <i class="fa-solid fa-{{ $monthGrowth >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }} text-[10px]"></i>
                {{ $monthGrowth >= 0 ? '+' : '' }}{{ $monthGrowth }}%
            </span>
        </div>
        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total Revenue</div>
        <div class="text-3xl font-bold mt-1 text-white">${{ number_format($stats['revenue'], 2) }}</div>
        <div class="text-xs text-gray-400 mt-2">This month: <span class="text-purple-300 font-semibold">${{ number_format($stats['monthly_revenue'], 2) }}</span></div>
    </div>

    {{-- Users --}}
    <div class="rounded-2xl bg-gray-900/60 backdrop-blur-sm border border-white/5 p-6 hover:border-white/10 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-500/15 flex items-center justify-center">
                <i class="fa-solid fa-users text-blue-400"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-500 hover:text-white transition-colors">View →</a>
        </div>
        <div class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Users</div>
        <div class="text-3xl font-bold mt-1">{{ number_format($stats['total_users']) }}</div>
        <div class="text-xs text-gray-500 mt-2">Registered accounts</div>
    </div>

    {{-- Add-ons --}}
    <div class="rounded-2xl bg-gray-900/60 backdrop-blur-sm border border-white/5 p-6 hover:border-white/10 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/15 flex items-center justify-center">
                <i class="fa-solid fa-puzzle-piece text-emerald-400"></i>
            </div>
            <a href="{{ route('admin.addons.index') }}" class="text-xs text-gray-500 hover:text-white transition-colors">View →</a>
        </div>
        <div class="text-xs text-gray-500 font-medium uppercase tracking-wider">Add-ons</div>
        <div class="text-3xl font-bold mt-1">{{ number_format($stats['total_addons']) }}</div>
        <div class="text-xs text-gray-500 mt-2">Published products</div>
    </div>

    {{-- Purchases --}}
    <div class="rounded-2xl bg-gray-900/60 backdrop-blur-sm border border-white/5 p-6 hover:border-white/10 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/15 flex items-center justify-center">
                <i class="fa-solid fa-credit-card text-amber-400"></i>
            </div>
            <a href="{{ route('admin.purchases.index') }}" class="text-xs text-gray-500 hover:text-white transition-colors">View →</a>
        </div>
        <div class="text-xs text-gray-500 font-medium uppercase tracking-wider">Purchases</div>
        <div class="text-3xl font-bold mt-1">{{ number_format($stats['total_purchases']) }}</div>
        <div class="text-xs text-gray-500 mt-2">Total orders</div>
    </div>
</div>

{{-- Secondary metrics --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    @php
        $minis = [
            ['label' => 'Projects', 'value' => $stats['total_projects'], 'icon' => 'fa-briefcase', 'color' => 'text-pink-400 bg-pink-500/10'],
            ['label' => 'Articles', 'value' => $stats['total_articles'], 'icon' => 'fa-newspaper', 'color' => 'text-sky-400 bg-sky-500/10'],
            ['label' => 'Services', 'value' => $stats['total_services'], 'icon' => 'fa-concierge-bell', 'color' => 'text-teal-400 bg-teal-500/10'],
            ['label' => 'Today', 'value' => $stats['visits_today'], 'icon' => 'fa-eye', 'color' => 'text-indigo-400 bg-indigo-500/10', 'suffix' => ' visits'],
            ['label' => 'This Week', 'value' => $stats['visits_week'], 'icon' => 'fa-calendar-week', 'color' => 'text-indigo-400 bg-indigo-500/10', 'suffix' => ' visits'],
            ['label' => 'This Month', 'value' => $stats['visits_month'], 'icon' => 'fa-chart-line', 'color' => 'text-indigo-400 bg-indigo-500/10', 'suffix' => ' visits'],
        ];
    @endphp
    @foreach($minis as $m)
    <div class="rounded-xl bg-gray-900/60 border border-white/5 p-4 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg {{ $m['color'] }} flex items-center justify-center shrink-0">
            <i class="fa-solid {{ $m['icon'] }} text-sm"></i>
        </div>
        <div class="min-w-0">
            <div class="text-lg font-bold leading-tight">{{ number_format($m['value']) }}</div>
            <div class="text-[11px] text-gray-500 leading-tight truncate">{{ $m['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Chart + Recent users --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 rounded-2xl bg-gray-900/60 border border-white/5 overflow-hidden">
        <div class="p-5 border-b border-white/5 flex items-center justify-between">
            <div>
                <h2 class="font-semibold">Revenue Trend</h2>
                <p class="text-xs text-gray-500 mt-0.5">Last 6 months</p>
            </div>
            <span class="text-sm text-gray-400">${{ number_format(array_sum(array_column($stats['monthly_chart'], 'revenue')), 2) }} total</span>
        </div>
        <div class="p-5">
            <div class="flex items-end gap-3 h-52">
                @foreach($stats['monthly_chart'] as $month)
                @php $h = $stats['max_revenue'] > 0 ? max(($month['revenue'] / $stats['max_revenue']) * 100, 3) : 3; @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group">
                    <span class="text-[11px] font-medium text-gray-500 group-hover:text-purple-300 transition-colors">${{ number_format($month['revenue'], 0) }}</span>
                    <div class="w-full flex-1 flex flex-col justify-end">
                        <div class="w-full rounded-t-md hover: hover: transition-all"
                            style="height: {{ $h }}%">
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 font-medium">{{ $month['month'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-gray-900/60 border border-white/5 overflow-hidden">
        <div class="p-5 border-b border-white/5 flex items-center justify-between">
            <h2 class="font-semibold">Recent Users</h2>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-purple-400 hover:text-purple-300">View All</a>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($stats['recent_users'] as $user)
            <div class="px-5 py-3.5 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                </div>
                <span class="text-[11px] text-gray-500 shrink-0">{{ $user->created_at->diffForHumans(null, true) }}</span>
            </div>
            @empty
            <p class="p-6 text-center text-gray-500 text-sm">No users yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent purchases --}}
<div class="rounded-2xl bg-gray-900/60 border border-white/5 overflow-hidden">
    <div class="p-5 border-b border-white/5 flex items-center justify-between">
        <div>
            <h2 class="font-semibold">Recent Purchases</h2>
            <p class="text-xs text-gray-500 mt-0.5">Last 10 orders</p>
        </div>
        <a href="{{ route('admin.purchases.index') }}" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">View All →</a>
    </div>
    @if($stats['recent_purchases']->count())
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead class="bg-white/[0.02]">
                <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                    <th class="px-5 py-3 font-medium">Customer</th>
                    <th class="px-5 py-3 font-medium">Add-on</th>
                    <th class="px-5 py-3 font-medium">Amount</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($stats['recent_purchases'] as $purchase)
                <tr class="hover:bg-white/[0.03] transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($purchase->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium truncate">{{ $purchase->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $purchase->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-gray-300">{{ $purchase->addon->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3.5 font-semibold">${{ number_format($purchase->amount, 2) }}</td>
                    <td class="px-5 py-3.5">
                        @php
                            $statusColors = [
                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                'failed' => 'bg-red-500/10 text-red-400 border-red-500/20',
                            ];
                            $sc = $statusColors[$purchase->status] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20';
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium border {{ $sc }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $purchase->created_at->format('M d, Y · H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="p-10 text-center text-gray-500">No purchases yet.</p>
    @endif
</div>
@endsection
