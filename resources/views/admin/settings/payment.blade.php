@extends('layouts.admin')

@section('page_title', 'Payment Settings')
@section('page_subtitle', 'PayPal credentials & checkout configuration')

@section('content')
<div>
    @include('admin.settings._tabs')

    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        {{ session('error') }}
    </div>
    @endif

    <div class="space-y-8">
        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <div class="flex items-start gap-3">
                <i class="fa-brands fa-paypal text-blue-400 text-2xl mt-1"></i>
                <div>
                    <h2 class="text-lg font-semibold">PayPal Configuration</h2>
                    <p class="text-sm text-gray-400 mt-1">
                        Credentials are loaded from the server's <code class="text-purple-400">.env</code> file
                        and cannot be changed from the admin panel for security.
                    </p>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-sm flex gap-3">
                <i class="fa-solid fa-lock mt-0.5"></i>
                <div>
                    <strong>Locked.</strong> To change the PayPal account, edit
                    <code class="text-amber-200">PAYPAL_MODE</code>,
                    <code class="text-amber-200">PAYPAL_CLIENT_ID</code> and
                    <code class="text-amber-200">PAYPAL_CLIENT_SECRET</code> in the server's
                    <code class="text-amber-200">.env</code> file, then run
                    <code class="text-amber-200">php artisan config:clear</code>.
                </div>
            </div>

            {{-- Mode (read-only) --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Mode</label>
                <div class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white flex items-center justify-between">
                    <span>
                        @if($settings['paypal_mode'] === 'live')
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                Live (production)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                Sandbox (testing)
                            </span>
                        @endif
                    </span>
                    <i class="fa-solid fa-lock text-gray-500 text-xs"></i>
                </div>
            </div>

            {{-- Client ID (masked) --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Client ID</label>
                <div class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-gray-300 font-mono text-sm flex items-center justify-between gap-3">
                    <span class="truncate">
                        @php
                            $cid = (string) $settings['paypal_client_id'];
                            $cidMasked = $cid !== '' ? substr($cid, 0, 6) . str_repeat('•', max(0, strlen($cid) - 10)) . substr($cid, -4) : '— not set —';
                        @endphp
                        {{ $cidMasked }}
                    </span>
                    <i class="fa-solid fa-lock text-gray-500 text-xs shrink-0"></i>
                </div>
            </div>

            {{-- Client Secret (masked) --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Client Secret</label>
                <div class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-gray-300 font-mono text-sm flex items-center justify-between gap-3">
                    <span class="truncate">
                        @php
                            $sec = (string) $settings['paypal_client_secret'];
                            $secMasked = $sec !== '' ? substr($sec, 0, 4) . str_repeat('•', max(0, strlen($sec) - 8)) . substr($sec, -4) : '— not set —';
                        @endphp
                        {{ $secMasked }}
                    </span>
                    <i class="fa-solid fa-lock text-gray-500 text-xs shrink-0"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
