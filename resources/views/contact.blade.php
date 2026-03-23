@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-purple-400 text-sm font-semibold uppercase tracking-widest">Get in Touch</span>
            <h1 class="text-4xl lg:text-6xl font-bold mt-2">Contact Us</h1>
            <p class="text-gray-400 mt-4">Have a question or project in mind? We'd love to hear from you.</p>
        </div>
        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6" data-aos="fade-up" data-aos-delay="100">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Your Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition-colors">
                    @error('name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition-colors">
                    @error('email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition-colors">
                @error('subject') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Message</label>
                <textarea name="message" rows="6" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3.5 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 focus:outline-none transition-colors resize-none">{{ old('message') }}</textarea>
                @error('message') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="text-center">
                <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white font-semibold px-10 py-4 rounded-xl transition-all hover:shadow-xl hover:shadow-purple-500/25">Send Message</button>
            </div>
        </form>
    </div>
</div>
@endsection
