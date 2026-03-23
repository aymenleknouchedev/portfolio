@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Waitlist</h1>
<div class="rounded-xl bg-gray-900 border border-white/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-gray-400 border-b border-white/5"><th class="text-left p-4">Email</th><th class="text-left p-4">Course</th><th class="text-left p-4">Date</th></tr></thead>
        <tbody>
            @foreach($entries as $entry)
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="p-4">{{ $entry->email }}</td>
                <td class="p-4 text-gray-400">{{ $entry->course_name }}</td>
                <td class="p-4 text-gray-400">{{ $entry->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($entries->isEmpty()) <p class="p-8 text-center text-gray-400">No waitlist entries yet.</p> @endif
</div>
<div class="mt-6">{{ $entries->links() }}</div>
@endsection
