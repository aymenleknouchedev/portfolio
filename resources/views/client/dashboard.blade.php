@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold">My Purchases</h1>
            <p class="text-gray-400 mt-1">View and download your purchased add-ons.</p>
        </div>

        @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-5 py-4 rounded-xl">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Purchased Add-ons --}}
        <div class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
            <div class="p-6 border-b border-white/5">
                <h2 class="text-lg font-semibold">My Purchases</h2>
            </div>

            @if($purchases->count() > 0)
            <div class="divide-y divide-white/5">
                @foreach($purchases as $purchase)
                <div class="p-6 flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-4">
                        {{-- Addon icon --}}
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ $purchase->addon->name }}</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-sm text-gray-400">{{ $purchase->created_at->format('M d, Y') }}</span>
                                @if($purchase->amount > 0)
                                <span class="text-xs bg-purple-500/10 text-purple-400 border border-purple-500/20 px-2 py-0.5 rounded-full">${{ number_format($purchase->amount, 2) }}</span>
                                @else
                                <span class="text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded-full">Free</span>
                                @endif
                            </div>
                            {{-- License key --}}
                            @if($purchase->license)
                            <div class="mt-2 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                <code class="text-xs bg-white/5 text-emerald-400 px-2 py-0.5 rounded font-mono select-all tracking-wide">{{ $purchase->license->key }}</code>
                                <button
                                    x-data="{ copied: false }"
                                    @click="
                                        navigator.clipboard.writeText('{{ $purchase->license->key }}');
                                        copied = true;
                                        setTimeout(() => copied = false, 2000)
                                    "
                                    class="flex items-center gap-1 text-xs text-gray-500 hover:text-white transition-colors duration-150"
                                    title="Copy license key">
                                    <span x-show="!copied" class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Copy
                                    </span>
                                    <span x-show="copied" x-cloak class="flex items-center gap-1 text-emerald-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Copied!
                                    </span>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3">
                        @if($purchase->download_token && !$purchase->isExpired())
                        <a href="{{ route('download', $purchase->download_token) }}"
                            class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-800 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <p class="text-gray-400 mb-4">You haven't purchased any add-ons yet.</p>
                <a href="{{ route('shop.index') }}"
                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Browse Shop
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection