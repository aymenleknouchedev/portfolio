@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Purchases</h1>
<div class="rounded-xl bg-gray-900 border border-white/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-gray-400 border-b border-white/5"><th class="text-left p-4">User</th><th class="text-left p-4">Add-on</th><th class="text-left p-4">Amount</th><th class="text-left p-4">Status</th><th class="text-left p-4">Token</th><th class="text-left p-4">Date</th></tr></thead>
        <tbody>
            @foreach($purchases as $purchase)
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="p-4">{{ $purchase->user->name }}</td>
                <td class="p-4">{{ $purchase->addon->name }}</td>
                <td class="p-4">${{ number_format($purchase->amount, 2) }}</td>
                <td class="p-4"><span class="px-2 py-1 rounded-full text-xs {{ $purchase->status === 'completed' ? 'bg-green-500/20 text-green-400' : ($purchase->status === 'failed' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-400') }}">{{ $purchase->status }}</span></td>
                <td class="p-4 text-gray-500 font-mono text-xs">{{ Str::limit($purchase->download_token, 16) }}</td>
                <td class="p-4 text-gray-400">{{ $purchase->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($purchases->isEmpty()) <p class="p-8 text-center text-gray-400">No purchases yet.</p> @endif
</div>
<div class="mt-6">{{ $purchases->links() }}</div>
@endsection
