@extends('layouts.admin')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Payment Settings</h1>
            <p class="text-gray-400 text-sm mt-1">Configure your PayPal credentials</p>
        </div>
    </div>

    {{-- Settings Navigation --}}
    <div class="flex gap-2 mb-8 flex-wrap">
        <a href="{{ route('admin.settings.hero') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Hero</a>
        <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">General</a>
        <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Social Media</a>
        <a href="{{ route('admin.settings.about') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">About Section</a>
        <a href="{{ route('admin.settings.account') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Account</a>
        <a href="{{ route('admin.settings.payment') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-purple-600 text-white">Payment</a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.payment.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-brands fa-paypal text-blue-400"></i>
                PayPal Configuration
            </h2>

            <p class="text-sm text-gray-400">
                Get your credentials from
                <a href="https://developer.paypal.com/dashboard/applications" target="_blank" class="text-purple-400 hover:underline">developer.paypal.com</a>
                → My Apps &amp; Credentials.
            </p>

            {{-- Mode --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Mode</label>
                <select name="paypal_mode" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
                    <option value="sandbox" @selected(old('paypal_mode', $settings['paypal_mode']) === 'sandbox')>Sandbox (testing)</option>
                    <option value="live" @selected(old('paypal_mode', $settings['paypal_mode']) === 'live')>Live (production)</option>
                </select>
                @error('paypal_mode') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Client ID --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Client ID</label>
                <input type="text" name="paypal_client_id"
                    value="{{ old('paypal_client_id', $settings['paypal_client_id']) }}"
                    placeholder="AXxx..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none font-mono text-sm"
                    autocomplete="off">
                @error('paypal_client_id') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Client Secret --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Client Secret</label>
                <input type="password" name="paypal_client_secret"
                    value="{{ old('paypal_client_secret', $settings['paypal_client_secret']) }}"
                    placeholder="EGxx..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none font-mono text-sm"
                    autocomplete="new-password">
                @error('paypal_client_secret') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
