@extends('layouts.admin')

@section('page_title', 'Waitlist')
@section('page_subtitle', $entries->total() . ' ' . Str::plural('signup', $entries->total()))

@section('content')
<div class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[480px]">
            <thead>
                <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Email</th>
                    <th class="px-6 py-4 font-medium">Course</th>
                    <th class="px-6 py-4 font-medium">Signed up</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($entries as $entry)
                <tr class="transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($entry->email, 0, 1)) }}
                            </div>
                            <span class="text-gray-200">{{ $entry->email }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400">{{ $entry->course_name }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $entry->created_at->format('M d, Y · H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-16 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-users text-gray-600"></i>
                        </div>
                        <p class="text-gray-400 text-sm">No waitlist signups yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($entries->hasPages())
    <div class="p-4 border-t border-white/5">
        {{ $entries->links() }}
    </div>
    @endif
</div>
@endsection
