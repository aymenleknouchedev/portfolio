@extends('layouts.admin')

@section('content')
<div class="max-w-3xl">
    <div class="mb-8">
        <a href="{{ route('admin.reclamations.index') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Reclamations
        </a>
    </div>

    <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 space-y-6">
        <div class="flex items-start justify-between gap-3 flex-wrap">
            <div>
                <h1 class="text-xl font-bold">{{ $reclamation->subject }}</h1>
                <p class="text-gray-400 text-sm mt-1">
                    From <span class="text-white font-medium">{{ $reclamation->user->name }}</span>
                    &lt;{{ $reclamation->user->email }}&gt;
                </p>
                <p class="text-gray-500 text-xs mt-1">{{ $reclamation->created_at->format('M d, Y \a\t H:i') }}</p>
                @if($reclamation->purchase && $reclamation->purchase->addon)
                <p class="text-gray-400 text-sm mt-2">Related purchase: <span class="text-white">{{ $reclamation->purchase->addon->name }}</span>
                    <span class="text-gray-600 text-xs">— {{ $reclamation->purchase->created_at->format('M d, Y') }}</span>
                </p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full border text-xs font-medium {{ $reclamation->statusColor() }}">{{ $reclamation->statusLabel() }}</span>
                <form action="{{ route('admin.reclamations.destroy', $reclamation) }}" method="POST"
                    onsubmit="return confirm('Delete this reclamation?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium flex items-center gap-1.5">
                        <i class="fa-solid fa-trash-can text-xs"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="border-t border-white/5 pt-6">
            <h3 class="text-sm font-semibold text-gray-400 mb-2">Client message</h3>
            <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $reclamation->message }}</div>
        </div>

        @if($reclamation->admin_reply)
        <div class="border-t border-white/5 pt-6">
            <div class="rounded-xl bg-purple-500/5 border border-purple-500/20 p-5">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-purple-300">Your reply</h3>
                    <span class="text-xs text-gray-500">{{ $reclamation->replied_at?->format('M d, Y H:i') }}</span>
                </div>
                <div class="text-gray-200 leading-relaxed whitespace-pre-line">{{ $reclamation->admin_reply }}</div>
            </div>
        </div>
        @endif

        <div class="border-t border-white/5 pt-6">
            <h3 class="text-sm font-semibold mb-3">{{ $reclamation->admin_reply ? 'Update reply' : 'Reply to client' }}</h3>

            @if($errors->any())
            <div class="mb-3 p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('admin.reclamations.reply', $reclamation) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-gray-400 mb-2">Message</label>
                    <textarea name="admin_reply" rows="6" required maxlength="5000"
                        class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-purple-500 focus:outline-none resize-none">{{ old('admin_reply', $reclamation->admin_reply) }}</textarea>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <select name="status" class="bg-gray-950 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:border-purple-500 focus:outline-none">
                        @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $reclamation->status) === $val ? 'selected' : '' }} class="bg-gray-950">{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all hover:shadow-lg hover:shadow-purple-500/25 inline-flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        Send Reply
                    </button>
                    <a href="mailto:{{ $reclamation->user->email }}?subject=Re: {{ urlencode($reclamation->subject) }}"
                        class="text-sm text-gray-400 hover:text-white inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-envelope text-xs"></i> Email instead
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
