<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Welcome back</h1>
        <p class="text-gray-400 mt-1 text-sm">Sign in to your {{ \App\Models\Setting::get('site_name', 'FraxionFX') }} account</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @include('auth.partials.google-signin', ['label' => 'Continue with Google'])

    <p class="text-center text-sm text-gray-400 mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 font-medium transition-colors">Create one</a>
    </p>
</x-guest-layout>
