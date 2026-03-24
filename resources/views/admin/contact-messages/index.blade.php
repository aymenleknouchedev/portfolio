@extends('layouts.admin')

@section('content')
<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Contact Messages</h1>
            <p class="text-gray-400 text-sm mt-1">{{ $unreadCount }} unread message{{ $unreadCount !== 1 ? 's' : '' }}</p>
        </div>
    </div>

    <div class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-white/5 text-gray-400 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">From</th>
                    <th class="px-6 py-4">Subject</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($messages as $msg)
                <tr class="{{ !$msg->is_read ? 'bg-purple-500/5' : '' }} hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4">
                        @if(!$msg->is_read)
                            <span class="inline-flex items-center gap-1.5 text-purple-400 text-xs font-medium">
                                <span class="w-2 h-2 rounded-full bg-purple-400"></span> New
                            </span>
                        @else
                            <span class="text-gray-500 text-xs">Read</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-white">{{ $msg->name }}</div>
                        <div class="text-gray-500 text-xs">{{ $msg->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ Str::limit($msg->subject, 50) }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $msg->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.contact-messages.show', $msg) }}"
                                class="text-purple-400 hover:text-purple-300 text-xs font-medium">View</a>
                            <form action="{{ route('admin.contact-messages.destroy', $msg) }}" method="POST"
                                onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No messages yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $messages->links() }}</div>
</div>
@endsection
