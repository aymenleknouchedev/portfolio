@extends('layouts.admin')

@section('page_title', 'Users')
@section('page_subtitle', $users->total() . ' registered ' . ($users->total() === 1 ? 'user' : 'users'))

@section('content')
<div class="flex items-center justify-between mb-6 gap-3 flex-wrap">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2 flex-1 max-w-xl">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email…"
                class="w-full bg-gray-900/60 border border-white/10 rounded-xl pl-11 pr-4 py-2.5 text-white text-sm focus:border-purple-500 focus:outline-none placeholder-gray-500">
        </div>
        @if(request('search'))
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white text-sm px-3 py-2.5">Clear</a>
        @endif
    </form>
    <a href="{{ route('admin.users.export') }}{{ request('search') ? '?search=' . urlencode(request('search')) : '' }}"
        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all">
        <i class="fa-solid fa-file-csv text-xs"></i>
        Export CSV
    </a>
</div>

<div class="rounded-2xl bg-gray-900/60 border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead class="bg-white/[0.02]">
                <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">User</th>
                    <th class="px-6 py-4 font-medium">Email</th>
                    <th class="px-6 py-4 font-medium">Role</th>
                    <th class="px-6 py-4 font-medium">Registered</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                <tr class="hover:bg-white/[0.03] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-violet-700 flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium truncate">{{ $user->name }}</div>
                                <div class="text-[11px] text-gray-500">#{{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium border
                            {{ $user->role === 'admin' ? 'bg-purple-500/10 text-purple-400 border-purple-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20' }}">
                            <i class="fa-solid fa-{{ $user->role === 'admin' ? 'shield-halved' : 'user' }} text-[10px]"></i>
                            {{ ucfirst($user->role ?? 'client') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-users text-gray-600"></i>
                        </div>
                        <p class="text-gray-400 text-sm">No users found.</p>
                    </td>
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
