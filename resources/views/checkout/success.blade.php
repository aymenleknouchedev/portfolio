@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-2xl mx-auto text-center">
        <div class="rounded-2xl bg-gray-900 border border-green-500/20 p-12" data-aos="fade-up">
            <div class="w-20 h-20 mx-auto rounded-full bg-green-500/20 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-3xl font-bold mb-3">Payment Successful!</h1>
            <p class="text-gray-400 mb-8">Thank you for your purchase. Your add-on is ready to download.</p>

            <div class="p-4 rounded-xl bg-white/5 border border-white/5 mb-8 text-left">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <div>
                        <h3 class="font-semibold">{{ $addon->name }}</h3>
                        <span class="text-sm text-gray-400">${{ number_format($purchase ? $purchase->amount : $addon->price, 2) }}</span>
                        @if($purchase && $purchase->license_tier)
                        <span class="ml-2 text-xs bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-0.5 rounded-full">{{ $purchase->license_tier }}</span>
                        @endif
                    </div>
                    @if($purchase && $purchase->download_token)
                    <a href="{{ route('download', $purchase->download_token) }}" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download
                    </a>
                    @endif
                </div>

                {{-- License keys --}}
                @if($purchase && $purchase->licenses && $purchase->licenses->count() > 0)
                <div class="mt-4 pt-4 border-t border-white/5">
                    <p class="text-xs text-gray-500 mb-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        {{ $purchase->licenses->count() }} License {{ Str::plural('Key', $purchase->licenses->count()) }}
                    </p>
                    <div class="space-y-2">
                        @foreach($purchase->licenses as $license)
                        <div class="flex items-center gap-2">
                            @if($purchase->licenses->count() > 1)
                            <span class="text-xs text-gray-600 w-5 shrink-0">#{{ $loop->iteration }}</span>
                            @endif
                            <code class="flex-1 text-xs bg-white/5 text-emerald-400 px-3 py-1.5 rounded-lg font-mono select-all tracking-wide break-all">{{ $license->key }}</code>
                            <button
                                x-data="{ copied: false }"
                                @click="navigator.clipboard.writeText('{{ $license->key }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="shrink-0 text-xs text-gray-500 hover:text-white transition-colors"
                                title="Copy">
                                <span x-show="!copied">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </span>
                                <span x-show="copied" x-cloak class="text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                <a href="{{ route('client.dashboard') }}" class="bg-white/10 hover:bg-white/15 text-white font-medium px-6 py-3 rounded-xl transition-all">Go to Dashboard</a>
                @endauth
                <a href="{{ route('shop.index') }}" class="bg-white/5 hover:bg-white/10 text-gray-300 font-medium px-6 py-3 rounded-xl transition-all">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
