@extends('layouts.admin')

@section('page_title', 'Contact Messages')
@section('page_subtitle', $unreadCount . ' unread · ' . $messages->total() . ' total')

@section('content')
<div class="rounded-2xl bg-gray-900/60 border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[760px]">
            <thead class="bg-white/[0.02]">
                <tr class="text-gray-400 text-left text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium w-4"></th>
                    <th class="px-6 py-4 font-medium">From</th>
                    <th class="px-6 py-4 font-medium">Subject</th>
                    <th class="px-6 py-4 font-medium">Date</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($messages as $msg)
                <tr class="hover:bg-white/[0.03] transition-colors {{ !$msg->is_read ? 'bg-purple-500/[0.04]' : '' }}">
                    <td class="pl-6 pr-2 py-4">
                        @if(!$msg->is_read)
                            <span class="w-2 h-2 rounded-full bg-purple-400 block" title="Unread"></span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($msg->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium truncate">{{ $msg->name }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $msg->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ Str::limit($msg->subject, 60) }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $msg->created_at->format('M d, Y · H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('admin.contact-messages.show', $msg) }}"
                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-purple-400 hover:bg-purple-500/10 transition-colors" title="View">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <form action="{{ route('admin.contact-messages.destroy', $msg) }}" method="POST" class="inline" onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-red-400 hover:bg-red-500/10 transition-colors" title="Delete">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                            <i class="fa-regular fa-envelope text-gray-600"></i>
                        </div>
                        <p class="text-gray-400 text-sm">No messages yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($messages->hasPages())
    <div class="p-4 border-t border-white/5">
        {{ $messages->links() }}
    </div>
    @endif
</div>
@endsection
