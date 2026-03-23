@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-2xl mx-auto text-center">
        <div class="rounded-2xl bg-gray-900 border border-amber-500/20 p-12" data-aos="fade-up">
            <div class="w-20 h-20 mx-auto rounded-full bg-amber-500/20 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h1 class="text-3xl font-bold mb-3">Payment Cancelled</h1>
            <p class="text-gray-400 mb-8">Your payment was cancelled. No charges have been made.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if($addon)
                <a href="{{ route('checkout.show', $addon->slug) }}" class="bg-purple-600 hover:bg-purple-500 text-white font-medium px-6 py-3 rounded-xl transition-all">Try Again</a>
                @endif
                <a href="{{ route('shop.index') }}" class="bg-white/5 hover:bg-white/10 text-gray-300 font-medium px-6 py-3 rounded-xl transition-all">Back to Shop</a>
            </div>
        </div>
    </div>
</div>
@endsection
