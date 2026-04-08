@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Users</h1>
    <a href="{{ route('admin.users.export') }}{{ request('search') ? '?search=' . urlencode(request('search')) : '' }}"
        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Export Emails (CSV)
    </a>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.users.index') }}" class="mb-6">
    <div class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
            class="flex-1 bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white focus:border-purple-500 focus:outline-none placeholder-gray-500">
        <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">Search</button>
        @if(request('search'))
        <a href="{{ route('admin.users.index') }}" class="bg-white/5 hover:bg-white/10 text-gray-300 font-medium px-6 py-3 rounded-xl transition-all">Clear</a>
        @endif
    </div>
</form>

<div class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
    <div class="p-4 border-b border-white/5 flex items-center justify-between">
        <span class="text-sm text-gray-400">{{ $users->total() }} user(s)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-left text-gray-500">
                    <th class="px-6 py-3 font-medium">#</th>
                    <th class="px-6 py-3 font-medium">Name</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Role</th>
                    <th class="px-6 py-3 font-medium">Registered</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 text-gray-500">{{ $user->id }}</td>
                    <td class="px-6 py-4 text-white font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $user->role === 'admin' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                            {{ ucfirst($user->role ?? 'client') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="p-4 border-t border-white/5">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
