@extends('layouts.admin')

@section('content')
<div class="max-w-3xl">
    <div class="mb-8">
        <a href="{{ route('admin.contact-messages.index') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Messages
        </a>
    </div>

    <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-bold">{{ $contactMessage->subject }}</h1>
                <p class="text-gray-400 text-sm mt-1">
                    From <span class="text-white font-medium">{{ $contactMessage->name }}</span>
                    &lt;{{ $contactMessage->email }}&gt;
                </p>
                <p class="text-gray-500 text-xs mt-1">{{ $contactMessage->created_at->format('M d, Y \a\t H:i') }}</p>
            </div>
            <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST"
                onsubmit="return confirm('Delete this message?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium flex items-center gap-1.5">
                    <i class="fa-solid fa-trash-can text-xs"></i> Delete
                </button>
            </form>
        </div>

        <div class="border-t border-white/5 pt-6">
            <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $contactMessage->message }}</div>
        </div>

        <div class="border-t border-white/5 pt-6">
            <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ urlencode($contactMessage->subject) }}"
                class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition-all hover:shadow-lg hover:shadow-purple-500/25">
                <i class="fa-solid fa-reply"></i> Reply via Email
            </a>
        </div>
    </div>
</div>
@endsection
