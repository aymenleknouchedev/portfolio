@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('client.reclamations.index') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Reclamations
            </a>
        </div>

        <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
            <div>
                <div class="flex items-start justify-between gap-3 flex-wrap">
                    <h1 class="text-xl font-bold">{{ $reclamation->subject }}</h1>
                    <span class="px-3 py-1 rounded-full border text-xs font-medium {{ $reclamation->statusColor() }}">{{ $reclamation->statusLabel() }}</span>
                </div>
                <p class="text-gray-500 text-xs mt-1">Submitted {{ $reclamation->created_at->format('M d, Y \a\t H:i') }}</p>
                @if($reclamation->purchase && $reclamation->purchase->addon)
                <p class="text-gray-400 text-sm mt-2">Related to: <span class="text-white">{{ $reclamation->purchase->addon->name }}</span></p>
                @endif
            </div>

            <div class="border-t border-white/5 pt-5">
                <h3 class="text-sm font-semibold text-gray-400 mb-2">Your message</h3>
                <div class="text-gray-200 leading-relaxed whitespace-pre-line">{{ $reclamation->message }}</div>
            </div>

            @if($reclamation->admin_reply)
            <div class="border-t border-white/5 pt-5">
                <div class="rounded-xl bg-purple-500/5 border border-purple-500/20 p-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-purple-300 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Admin reply
                        </h3>
                        <span class="text-xs text-gray-500">{{ $reclamation->replied_at?->diffForHumans() }}</span>
                    </div>
                    <div class="text-gray-200 leading-relaxed whitespace-pre-line">{{ $reclamation->admin_reply }}</div>
                </div>
            </div>
            @else
            <div class="border-t border-white/5 pt-5 text-sm text-gray-500 italic">Awaiting reply from our team...</div>
            @endif
        </div>
    </div>
</div>
@endsection
