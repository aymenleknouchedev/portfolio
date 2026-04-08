@extends('layouts.admin')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">{{ isset($promoCode) ? 'Edit Promo Code' : 'New Promo Code' }}</h1>

    <form action="{{ isset($promoCode) ? route('admin.promo-codes.update', $promoCode) : route('admin.promo-codes.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($promoCode)) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium mb-2">Code</label>
            <input type="text" name="code" value="{{ old('code', $promoCode->code ?? '') }}" placeholder="e.g. SAVE20" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none uppercase" required>
            @error('code') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Description <span class="text-gray-500">(optional)</span></label>
            <input type="text" name="description" value="{{ old('description', $promoCode->description ?? '') }}" placeholder="e.g. Summer sale discount" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2">Discount Type</label>
                <select name="type" class="w-full bg-gray-900 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
                    <option value="percentage" {{ old('type', $promoCode->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="fixed" {{ old('type', $promoCode->type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Value</label>
                <input type="number" name="value" value="{{ old('value', $promoCode->value ?? '') }}" step="0.01" min="0.01" placeholder="e.g. 20" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none" required>
                @error('value') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2">Min Order Amount <span class="text-gray-500">($)</span></label>
                <input type="number" name="min_order" value="{{ old('min_order', $promoCode->min_order ?? '') }}" step="0.01" min="0" placeholder="No minimum" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Max Discount <span class="text-gray-500">($, for %)</span></label>
                <input type="number" name="max_discount" value="{{ old('max_discount', $promoCode->max_discount ?? '') }}" step="0.01" min="0" placeholder="No cap" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2">Max Uses</label>
                <input type="number" name="max_uses" value="{{ old('max_uses', $promoCode->max_uses ?? '') }}" min="1" placeholder="Unlimited" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Times Used</label>
                <input type="text" value="{{ $promoCode->used_count ?? 0 }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-gray-500 cursor-not-allowed" disabled>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2">Starts At</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($promoCode->starts_at) ? $promoCode->starts_at->format('Y-m-d\TH:i') : '') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Expires At</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at', isset($promoCode->expires_at) ? $promoCode->expires_at->format('Y-m-d\TH:i') : '') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
                @error('expires_at') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promoCode->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded bg-white/5 border-white/10 text-purple-600 focus:ring-purple-500">
                <span class="text-sm font-medium">Active</span>
            </label>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-8 py-3 rounded-xl transition-all">{{ isset($promoCode) ? 'Update' : 'Create' }} Promo Code</button>
            <a href="{{ route('admin.promo-codes.index') }}" class="bg-white/5 hover:bg-white/10 text-white font-medium px-8 py-3 rounded-xl transition-all">Cancel</a>
        </div>
    </form>
</div>
@endsection
