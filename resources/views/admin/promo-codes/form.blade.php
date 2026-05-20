@extends('layouts.admin')

@section('page_title', isset($promoCode) ? 'Edit Promo Code' : 'New Promo Code')
@section('page_subtitle', isset($promoCode) ? $promoCode->code : 'Create a discount code for checkout')

@section('content')
<form action="{{ isset($promoCode) ? route('admin.promo-codes.update', $promoCode) : route('admin.promo-codes.store') }}" method="POST" class="space-y-6">
    @csrf
    @if(isset($promoCode)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            {{-- Code & description --}}
            <section class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-5">
                <header class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl section-header-icon flex items-center justify-center">
                        <i class="fa-solid fa-ticket text-amber-300 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold">Code & Label</h2>
                        <p class="text-xs text-gray-500">What the customer types at checkout.</p>
                    </div>
                </header>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Code</label>
                    <input type="text" name="code" value="{{ old('code', $promoCode->code ?? '') }}" placeholder="SAVE20"
                        class="w-full rounded-xl px-4 py-3 uppercase font-mono tracking-wider" required>
                    @error('code') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Description <span class="text-gray-600 normal-case">— optional</span></label>
                    <input type="text" name="description" value="{{ old('description', $promoCode->description ?? '') }}" placeholder="e.g. Summer sale discount"
                        class="w-full rounded-xl px-4 py-3">
                </div>
            </section>

            {{-- Discount config --}}
            <section class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-5">
                <header class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl section-header-icon flex items-center justify-center">
                        <i class="fa-solid fa-percent text-emerald-300 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold">Discount</h2>
                        <p class="text-xs text-gray-500">Choose percentage or fixed amount, plus optional caps.</p>
                    </div>
                </header>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Discount Type</label>
                        <select name="type" class="w-full rounded-xl px-4 py-3">
                            <option value="percentage" {{ old('type', $promoCode->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ old('type', $promoCode->type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Value</label>
                        <input type="number" name="value" value="{{ old('value', $promoCode->value ?? '') }}" step="0.01" min="0.01" placeholder="20" required
                            class="w-full rounded-xl px-4 py-3">
                        @error('value') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Min Order ($)</label>
                        <input type="number" name="min_order" value="{{ old('min_order', $promoCode->min_order ?? '') }}" step="0.01" min="0" placeholder="No minimum"
                            class="w-full rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Max Discount ($, for %)</label>
                        <input type="number" name="max_discount" value="{{ old('max_discount', $promoCode->max_discount ?? '') }}" step="0.01" min="0" placeholder="No cap"
                            class="w-full rounded-xl px-4 py-3">
                    </div>
                </div>
            </section>

            {{-- Validity --}}
            <section class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-5">
                <header class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl section-header-icon flex items-center justify-center">
                        <i class="fa-solid fa-calendar text-sky-300 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold">Validity & Usage</h2>
                        <p class="text-xs text-gray-500">Define when and how often this code works.</p>
                    </div>
                </header>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Max Uses</label>
                        <input type="number" name="max_uses" value="{{ old('max_uses', $promoCode->max_uses ?? '') }}" min="1" placeholder="Unlimited"
                            class="w-full rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Times Used</label>
                        <input type="text" value="{{ $promoCode->used_count ?? 0 }}" disabled
                            class="w-full rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Starts At</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($promoCode->starts_at) ? $promoCode->starts_at->format('Y-m-d\TH:i') : '') }}"
                            class="w-full rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Expires At</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at', isset($promoCode->expires_at) ? $promoCode->expires_at->format('Y-m-d\TH:i') : '') }}"
                            class="w-full rounded-xl px-4 py-3">
                        @error('expires_at') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>
        </div>

        <aside class="space-y-4">
            <div class="rounded-2xl bg-gradient-to-br from-purple-600/15 via-violet-700/10 to-gray-900 border border-purple-500/20 p-6">
                <h3 class="font-semibold mb-1">{{ isset($promoCode) ? 'Save Changes' : 'Create Code' }}</h3>
                <p class="text-xs text-gray-400 mb-4">Required fields are marked.</p>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold py-3 rounded-xl transition-all">
                    <i class="fa-solid fa-{{ isset($promoCode) ? 'floppy-disk' : 'plus' }}"></i>
                    {{ isset($promoCode) ? 'Update Code' : 'Create Code' }}
                </button>
                <a href="{{ route('admin.promo-codes.index') }}"
                    class="block w-full text-center mt-2 bg-white/5 hover:bg-white/10 text-gray-300 font-medium py-3 rounded-xl transition-all">
                    Cancel
                </a>
            </div>

            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <h3 class="font-semibold mb-3 text-sm">Status</h3>
                <label class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 cursor-pointer transition-colors">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promoCode->is_active ?? true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded bg-gray-950 border-white/10 text-purple-600 focus:ring-purple-500">
                    <div>
                        <div class="text-sm font-medium">Active</div>
                        <div class="text-[11px] text-gray-500">Customers can use this code at checkout.</div>
                    </div>
                </label>
            </div>
        </aside>
    </div>
</form>
@endsection
