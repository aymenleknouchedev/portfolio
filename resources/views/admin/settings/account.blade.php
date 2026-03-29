@extends('layouts.admin')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Account Settings</h1>
            <p class="text-gray-400 text-sm mt-1">Update your email and password</p>
        </div>
    </div>

    {{-- Settings Navigation --}}
    <div class="flex gap-2 mb-8 flex-wrap">
        <a href="{{ route('admin.settings.hero') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Hero</a>
        <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">General</a>
        <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Social Media</a>
        <a href="{{ route('admin.settings.about') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white transition-colors">About Section</a>
        <a href="{{ route('admin.settings.account') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-purple-600 text-white">Account</a>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-300 px-5 py-3 rounded-xl">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.settings.account.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-user text-purple-400"></i>
                Profile Information
            </h2>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none">
            </div>
        </div>

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-lock text-purple-400"></i>
                Change Password
            </h2>
            <p class="text-sm text-gray-500">Leave blank to keep current password</p>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Current Password</label>
                <input type="password" name="current_password"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                    placeholder="Enter current password">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                <input type="password" name="password"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                    placeholder="Enter new password">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none"
                    placeholder="Confirm new password">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-8 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-purple-500/25">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
