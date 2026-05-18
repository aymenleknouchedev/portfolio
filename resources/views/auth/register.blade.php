<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Create your account</h1>
        <p class="text-gray-400 mt-1 text-sm">Join {{ \App\Models\Setting::get('site_name', 'FraxionFX') }} to access premium assets</p>
    </div>

    @include('auth.partials.google-signin', ['label' => 'Sign up with Google'])

    <p class="text-center text-sm text-gray-400 mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-medium transition-colors">Sign in</a>
    </p>
</x-guest-layout>
