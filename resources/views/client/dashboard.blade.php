@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold">My Purchases</h1>
            <p class="text-gray-400 mt-1">View and download your purchased add-ons.</p>
        </div>

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
                        <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ $purchase->addon->name }}</h3>
                            <span class="text-sm text-gray-400">Purchased {{ $purchase->created_at->format('M d, Y') }}
                                · ${{ number_format($purchase->amount, 2) }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($purchase->download_token && !$purchase->isExpired())
                        <a href="{{ route('download', $purchase->download_token) }}"
                            class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3" />
                            </svg>
                            Download
                        </a>
                        @else
                        <span class="text-amber-400 text-sm">Link expired</span>
                        @endif

                        <form action="{{ route('client.regenerate-token', $purchase->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="text-sm bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 px-4 py-2.5 rounded-lg transition-all"
                                title="Generate new download link">
                                🔄 Regenerate
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <p class="text-gray-400 mb-4">You haven't purchased any add-ons yet.</p>
                <a href="{{ route('shop.index') }}"
                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">Browse
                    Shop</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection