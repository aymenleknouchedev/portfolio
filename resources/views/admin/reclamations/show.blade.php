@extends('layouts.admin')

@section('page_title', 'Reclamation')
@section('page_subtitle', $reclamation->subject)

@section('content')
<div>
    <a href="{{ route('admin.reclamations.index') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium inline-flex items-center gap-1.5 mb-6">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to Reclamations
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            {{-- Conversation card --}}
            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-base font-bold shrink-0">
                        {{ strtoupper(substr($reclamation->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl font-bold">{{ $reclamation->subject }}</h1>
                        <p class="text-sm text-gray-300 mt-1">
                            <span class="font-medium">{{ $reclamation->user->name }}</span>
                            <span class="text-gray-500">·</span>
                            <a href="mailto:{{ $reclamation->user->email }}" class="text-purple-400 hover:text-purple-300">{{ $reclamation->user->email }}</a>
                        </p>
                        <p class="text-xs text-gray-500 mt-1.5">
                            <i class="fa-regular fa-clock"></i> {{ $reclamation->created_at->format('M d, Y \a\t H:i') }}
                            <span class="text-gray-600">· {{ $reclamation->created_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>

                <div class="border-t border-white/5 pt-5">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Client message</h3>
                    <div class="text-gray-200 leading-relaxed whitespace-pre-line">{{ $reclamation->message }}</div>
                </div>

                @if($reclamation->admin_reply)
                <div class="border-t border-white/5 pt-5">
                    <div class="rounded-xl border border-purple-500/20 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-purple-300 flex items-center gap-2">
                                <i class="fa-solid fa-reply"></i> Your reply
                            </h3>
                            <span class="text-xs text-gray-500">{{ $reclamation->replied_at?->format('M d, Y · H:i') }}</span>
                        </div>
                        <div class="text-gray-200 leading-relaxed whitespace-pre-line">{{ $reclamation->admin_reply }}</div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Reply form --}}
            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <header class="mb-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl section-header-icon flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane text-purple-300 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold">{{ $reclamation->admin_reply ? 'Update reply' : 'Reply to client' }}</h2>
                        <p class="text-xs text-gray-500">The client will see a "new reply" badge in their dashboard.</p>
                    </div>
                </header>

                @if($errors->any())
                <div class="mb-3 p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('admin.reclamations.reply', $reclamation) }}" method="POST" class="space-y-4">
                    @csrf
                    <textarea name="admin_reply" rows="6" required maxlength="5000"
                        placeholder="Write your reply…"
                        class="w-full rounded-xl px-4 py-3 text-sm resize-none">{{ old('admin_reply', $reclamation->admin_reply) }}</textarea>

                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-gray-500 uppercase tracking-wider font-medium">Status</label>
                            <select name="status" class="rounded-xl px-4 py-2 text-sm">
                                @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $val => $label)
                                <option value="{{ $val }}" {{ old('status', $reclamation->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="mailto:{{ $reclamation->user->email }}?subject=Re: {{ urlencode($reclamation->subject) }}"
                                class="text-sm text-gray-400 hover:text-white inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-envelope text-xs"></i> Email instead
                            </a>
                            <button type="submit"
                                class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all inline-flex items-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                Send Reply
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-4">
            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <h3 class="font-semibold mb-4 text-sm">Status</h3>
                <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium border {{ $reclamation->statusColor() }}">{{ $reclamation->statusLabel() }}</span>
            </div>

            @if($reclamation->purchase && $reclamation->purchase->addon)
            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <h3 class="font-semibold mb-3 text-sm">Related Purchase</h3>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/15 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-puzzle-piece text-purple-300 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="font-medium text-sm truncate">{{ $reclamation->purchase->addon->name }}</div>
                        <div class="text-xs text-gray-500">{{ $reclamation->purchase->created_at->format('M d, Y') }} · ${{ number_format($reclamation->purchase->amount, 2) }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <h3 class="font-semibold mb-3 text-sm">Actions</h3>
                <form action="{{ route('admin.reclamations.destroy', $reclamation) }}" method="POST" onsubmit="return confirm('Delete this reclamation?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-left inline-flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                        <i class="fa-solid fa-trash-can text-xs w-4"></i>
                        Delete reclamation
                    </button>
                </form>
            </div>
        </aside>
    </div>
</div>
@endsection
