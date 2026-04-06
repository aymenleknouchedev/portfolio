@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
    $tiers = $addon->getEffectiveLicenseTiers();
    if ($addon->requires_license) {
        array_unshift($tiers, [
            'label' => 'Starter',
            'quantity' => 1,
            'price' => (float) $addon->price,
        ]);
    }
    $tiersJson = json_encode($tiers);
@endphp
<div class="pt-32 pb-24 px-4">
    <div class="max-w-2xl mx-auto" x-data="{
        tierIndex: 0,
        tiers: {{ $tiersJson }},
        basePrice: {{ $addon->price }},
        requiresLicense: {{ $addon->requires_license ? 'true' : 'false' }},
        get selectedTier() { return this.tiers[this.tierIndex] ?? null; },
        get total() {
            if (!this.requiresLicense) return this.basePrice;
            return this.selectedTier ? this.selectedTier.price : this.basePrice;
        }
    }">
        <div class="rounded-2xl bg-gray-900 border border-white/5 p-8" data-aos="fade-up">
            <h1 class="text-2xl font-bold mb-2">Checkout</h1>
            <p class="text-gray-400 mb-8">Complete your purchase to get instant access.</p>

            {{-- Addon summary --}}
            <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5 mb-6">
                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-500/20 to-violet-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold">{{ $addon->name }}</h3>
                    <span class="text-sm text-gray-400">{{ $addon->category->name ?? 'Digital Add-on' }}</span>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-purple-400">$<span x-text="total.toFixed(2)">{{ number_format($addon->price, 2) }}</span></div>
                </div>
            </div>

            {{-- License selector --}}
            @if($addon->requires_license)
            <div class="mb-6 space-y-4">

                {{-- Tier cards (shown only when multiple tiers) --}}
                <div>
                    <h4 class="text-sm font-medium text-white mb-3">Choose Your License</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-{{ min(count($tiers), 3) }} gap-3">
                        @foreach($tiers as $i => $tier)
                        <button type="button"
                            @click="tierIndex = {{ $i }}"
                            :class="tierIndex === {{ $i }} ? 'border-purple-500 bg-purple-500/10 text-white' : 'border-white/10 bg-white/5 text-gray-400 hover:border-white/20'"
                            class="p-4 rounded-xl border text-left transition-all duration-200 cursor-pointer">
                            <div class="font-semibold text-sm">{{ $tier['label'] }}</div>
                            <div class="text-2xl font-bold mt-1 text-purple-400">${{ number_format($tier['price'], 2) }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $tier['quantity'] ?? 1 }} {{ Str::plural('license', $tier['quantity'] ?? 1) }}</div>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Price breakdown --}}
                <div class="text-xs text-gray-500 space-y-1 px-1" x-show="selectedTier">
                    <div class="flex justify-between">
                        <span x-text="selectedTier ? selectedTier.label + ' — ' + selectedTier.quantity + ' lic.' : ''"></span>
                        <span x-text="selectedTier ? '$' + selectedTier.price.toFixed(2) : ''"></span>
                    </div>
                </div>
            </div>
            @endif

            <div class="border-t border-white/5 pt-6">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-400">Total</span>
                    <span class="text-2xl font-bold">$<span x-text="total.toFixed(2)">{{ number_format($addon->price, 2) }}</span></span>
                </div>

                @auth
                    <form action="{{ route('checkout.process', $addon->slug) }}" method="POST">
                        @csrf
                        <input type="hidden" name="tier_index" :value="tierIndex">
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-semibold py-4 rounded-xl transition-all hover:shadow-xl hover:shadow-purple-500/25 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Pay with PayPal
                        </button>
                    </form>
                    <p class="text-center text-gray-500 text-sm mt-4">Secure payment processed by PayPal</p>
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
