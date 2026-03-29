@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p class="text-gray-400 text-sm mt-1">Welcome back, {{ auth()->user()->name }}</p>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    {{-- Revenue --}}
    <div class="rounded-2xl bg-gradient-to-br from-purple-500/10 to-violet-500/5 border border-purple-500/10 p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-400 text-sm font-medium">Total Revenue</span>
            <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-purple-400">${{ number_format($stats['revenue'], 2) }}</div>
        <p class="text-xs text-gray-500 mt-1">This month: ${{ number_format($stats['monthly_revenue'], 2) }}</p>
    </div>

    {{-- Users --}}
    <div class="rounded-2xl bg-gradient-to-br from-blue-500/10 to-cyan-500/5 border border-blue-500/10 p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-400 text-sm font-medium">Total Users</span>
            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['total_users'] }}</div>
        <p class="text-xs text-gray-500 mt-1">Registered accounts</p>
    </div>

    {{-- Add-ons --}}
    <div class="rounded-2xl bg-gradient-to-br from-emerald-500/10 to-green-500/5 border border-emerald-500/10 p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-400 text-sm font-medium">Add-ons</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['total_addons'] }}</div>
        <p class="text-xs text-gray-500 mt-1">Published products</p>
    </div>

    {{-- Purchases --}}
    <div class="rounded-2xl bg-gradient-to-br from-amber-500/10 to-orange-500/5 border border-amber-500/10 p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-400 text-sm font-medium">Purchases</span>
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['total_purchases'] }}</div>
        <p class="text-xs text-gray-500 mt-1">Total orders</p>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="rounded-2xl bg-gray-900 border border-white/5 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-pink-500/10 border border-pink-500/10 flex items-center justify-center">
            <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div>
            <div class="text-2xl font-bold">{{ $stats['total_projects'] }}</div>
            <div class="text-sm text-gray-400">Projects</div>
        </div>
    </div>
    <div class="rounded-2xl bg-gray-900 border border-white/5 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-sky-500/10 border border-sky-500/10 flex items-center justify-center">
            <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <div class="text-2xl font-bold">{{ $stats['total_articles'] }}</div>
            <div class="text-sm text-gray-400">Articles</div>
        </div>
    </div>
    <div class="rounded-2xl bg-gray-900 border border-white/5 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/10 flex items-center justify-center">
            <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <div class="text-2xl font-bold">{{ $stats['total_services'] }}</div>
            <div class="text-sm text-gray-400">Services</div>
        </div>
    </div>
</div>

{{-- Visitor Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="rounded-2xl bg-gradient-to-br from-indigo-500/10 to-blue-500/5 border border-indigo-500/10 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
        <div>
            <div class="text-2xl font-bold">{{ $stats['visits_today'] }}</div>
            <div class="text-sm text-gray-400">Visitors Today</div>
        </div>
    </div>
    <div class="rounded-2xl bg-gradient-to-br from-indigo-500/10 to-blue-500/5 border border-indigo-500/10 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <div class="text-2xl font-bold">{{ $stats['visits_week'] }}</div>
            <div class="text-sm text-gray-400">Visitors This Week</div>
        </div>
    </div>
    <div class="rounded-2xl bg-gradient-to-br from-indigo-500/10 to-blue-500/5 border border-indigo-500/10 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
            <div class="text-2xl font-bold">{{ $stats['visits_month'] }}</div>
            <div class="text-sm text-gray-400">Visitors This Month</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
        <div class="p-5 border-b border-white/5 flex items-center justify-between">
            <h2 class="font-semibold">Revenue (Last 6 Months)</h2>
            <span class="text-sm text-gray-400">${{ number_format($stats['revenue'], 2) }} total</span>
        </div>
        <div class="p-5">
            <div class="flex items-end gap-3 h-48">
                @foreach($stats['monthly_chart'] as $month)
                <div class="flex-1 flex flex-col items-center gap-2">
                    <div class="w-full relative flex flex-col justify-end" style="height: 160px;">
                        <div class="w-full bg-gradient-to-t from-purple-600 to-purple-400 rounded-lg transition-all duration-500 hover:from-purple-500 hover:to-purple-300"
                            style="height: {{ $stats['max_revenue'] > 0 ? max(($month['revenue'] / $stats['max_revenue']) * 100, 4) : 4 }}%"
                            title="${{ number_format($month['revenue'], 2) }}">
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 font-medium">{{ $month['month'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
        <div class="p-5 border-b border-white/5">
            <h2 class="font-semibold">Recent Users</h2>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($stats['recent_users'] as $user)
            <div class="px-5 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center text-xs font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                </div>
                <span class="text-xs text-gray-500 ml-auto shrink-0">{{ $user->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="p-5 text-center text-gray-400 text-sm">No users yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent Purchases Table --}}
<div class="mt-6 rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
    <div class="p-5 border-b border-white/5 flex items-center justify-between">
        <h2 class="font-semibold">Recent Purchases</h2>
        <a href="{{ route('admin.purchases.index') }}" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">View All →</a>
    </div>
    @if($stats['recent_purchases']->count())
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-400 border-b border-white/5 text-left">
                    <th class="px-5 py-3 font-medium">User</th>
                    <th class="px-5 py-3 font-medium">Add-on</th>
                    <th class="px-5 py-3 font-medium">Amount</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['recent_purchases'] as $purchase)
                <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-purple-500/30 to-violet-500/30 flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($purchase->user->name ?? 'U', 0, 1)) }}
                            </div>
                            {{ $purchase->user->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-300">{{ $purchase->addon->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 font-medium">${{ number_format($purchase->amount, 2) }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $purchase->status === 'completed' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $purchase->status === 'completed' ? 'bg-green-400' : 'bg-amber-400' }}"></span>
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400">{{ $purchase->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="p-8 text-center text-gray-400">No purchases yet.</p>
    @endif
</div>
@endsection