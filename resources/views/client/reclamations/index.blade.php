@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-3xl font-bold">My Reclamations</h1>
                <p class="text-gray-400 mt-1">Send us an issue or question and track our reply.</p>
            </div>
            <a href="{{ route('client.dashboard') }}"
                class="text-sm text-gray-400 hover:text-white inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Purchases
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-5 py-4 rounded-xl">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            {{-- Submit form --}}
            <div class="lg:col-span-2">
                <div class="rounded-2xl bg-gray-900 border border-white/5 p-6 sticky top-28">
                    <h2 class="text-lg font-semibold mb-4">New Reclamation</h2>

                    @if($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <form action="{{ route('client.reclamations.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Related Purchase <span class="text-xs text-gray-500">(optional)</span></label>
                            <select name="purchase_id" class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-purple-500 focus:outline-none">
                                <option value="" class="bg-gray-950">— None —</option>
                                @foreach($purchases as $p)
                                <option value="{{ $p->id }}" {{ old('purchase_id', $selectedPurchaseId) == $p->id ? 'selected' : '' }} class="bg-gray-950">
                                    {{ $p->addon->name ?? 'Addon' }} — {{ $p->created_at->format('M d, Y') }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="200"
                                placeholder="Brief summary"
                                class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-purple-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Message</label>
                            <textarea name="message" required rows="6" maxlength="5000"
                                placeholder="Describe your issue in detail..."
                                class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-purple-500 focus:outline-none resize-none">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-purple-600 hover:bg-purple-500 text-white font-semibold py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-purple-500/25 inline-flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Submit Reclamation
                        </button>
                    </form>
                </div>
            </div>

            {{-- List --}}
            <div class="lg:col-span-3">
                <div class="rounded-2xl bg-gray-900 border border-white/5 overflow-hidden">
                    <div class="p-5 border-b border-white/5">
                        <h2 class="text-lg font-semibold">History</h2>
                    </div>

                    @if($reclamations->count() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach($reclamations as $r)
                        <a href="{{ route('client.reclamations.show', $r) }}"
                           class="block p-5 hover:bg-white/5 transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-semibold truncate">{{ $r->subject }}</h3>
                                        @if(!$r->is_read_client && $r->admin_reply)
                                        <span class="inline-flex items-center gap-1 text-purple-400 text-xs font-medium">
                                            <span class="w-2 h-2 rounded-full bg-purple-400"></span> New reply
                                        </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-400 mt-1 line-clamp-2">{{ Str::limit($r->message, 120) }}</p>
                                    <div class="flex items-center gap-2 mt-2 text-xs">
                                        <span class="px-2 py-0.5 rounded-full border {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span>
                                        @if($r->purchase && $r->purchase->addon)
                                        <span class="text-gray-500">— {{ $r->purchase->addon->name }}</span>
                                        @endif
                                        <span class="text-gray-600">· {{ $r->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="p-10 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-gray-800 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="text-gray-400">No reclamations yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
