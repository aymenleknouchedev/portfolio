@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-2xl bg-gray-900 border border-white/5 p-8" data-aos="fade-up">
            <h1 class="text-2xl font-bold mb-2">Checkout</h1>
            <p class="text-gray-400 mb-8">Complete your purchase to get instant access.</p>

            <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5 mb-8">
                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-500/20 to-violet-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold">{{ $addon->name }}</h3>
                    <span class="text-sm text-gray-400">{{ $addon->category->name ?? 'Digital Add-on' }}</span>
                </div>
                <div class="text-2xl font-bold text-purple-400">${{ number_format($addon->price, 2) }}</div>
            </div>

            <div class="border-t border-white/5 pt-6">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-400">Total</span>
                    <span class="text-2xl font-bold">${{ number_format($addon->price, 2) }}</span>
                </div>

                @auth
                    <form action="{{ route('checkout.process', $addon->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-semibold py-4 rounded-xl transition-all hover:shadow-xl hover:shadow-purple-500/25 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Pay with Stripe
                        </button>
                    </form>
                    <p class="text-center text-gray-500 text-sm mt-4">Secure payment processed by Stripe</p>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center bg-purple-600 hover:bg-purple-500 text-white font-semibold py-4 rounded-xl transition-all">
                        Sign In to Purchase
                    </a>
                    <p class="text-center text-gray-500 text-sm mt-3">
                        Don't have an account? <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300">Create one</a>
                    </p>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
