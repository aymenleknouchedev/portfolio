@extends('layouts.admin')

@section('page_title', 'Message')
@section('page_subtitle', $contactMessage->subject)

@section('content')
<div>
    <a href="{{ route('admin.contact-messages.index') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium inline-flex items-center gap-1.5 mb-6">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to Messages
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-violet-700 flex items-center justify-center text-base font-bold shrink-0 shadow-lg shadow-purple-500/30">
                    {{ strtoupper(substr($contactMessage->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-bold">{{ $contactMessage->subject }}</h1>
                    <p class="text-sm text-gray-300 mt-1">
                        <span class="font-medium">{{ $contactMessage->name }}</span>
                        <span class="text-gray-500">·</span>
                        <a href="mailto:{{ $contactMessage->email }}" class="text-purple-400 hover:text-purple-300">{{ $contactMessage->email }}</a>
                    </p>
                    <p class="text-xs text-gray-500 mt-1.5">
                        <i class="fa-regular fa-clock"></i> {{ $contactMessage->created_at->format('M d, Y \a\t H:i') }}
                        <span class="text-gray-600">· {{ $contactMessage->created_at->diffForHumans() }}</span>
                    </p>
                </div>
            </div>

            <div class="border-t border-white/5 pt-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Message</h3>
                <div class="text-gray-200 leading-relaxed whitespace-pre-line">{{ $contactMessage->message }}</div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-2xl bg-gradient-to-br from-purple-600/15 via-violet-700/10 to-gray-900 border border-purple-500/20 p-6">
                <h3 class="font-semibold mb-1">Quick Reply</h3>
                <p class="text-xs text-gray-400 mb-4">Open your mail client with a prefilled subject.</p>
                <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ urlencode($contactMessage->subject) }}"
                    class="block w-full text-center bg-purple-600 hover:bg-purple-500 text-white font-semibold py-3 rounded-xl transition-all hover:shadow-xl hover:shadow-purple-500/30">
                    <i class="fa-solid fa-reply mr-1.5"></i> Reply via Email
                </a>
            </div>

            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <h3 class="font-semibold mb-3 text-sm">Actions</h3>
                <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" onsubmit="return confirm('Delete this message?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-left inline-flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                        <i class="fa-solid fa-trash-can text-xs w-4"></i>
                        Delete message
                    </button>
                </form>
            </div>
        </aside>
    </div>
</div>
@endsection
