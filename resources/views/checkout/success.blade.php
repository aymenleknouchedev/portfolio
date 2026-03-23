@extends('layouts.app')

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
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold">{{ $addon->name }}</h3>
                        <span class="text-sm text-gray-400">${{ number_format($addon->price, 2) }}</span>
                    </div>
                    @if($purchase && $purchase->download_token)
                    <a href="{{ route('download', $purchase->download_token) }}" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download
                    </a>
                    @endif
                </div>
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
