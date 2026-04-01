<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $purchases = $request->user()->purchases()
            ->with(['addon', 'license'])
            ->where('status', 'completed')
            ->latest()
            ->get();

        return view('client.dashboard', compact('purchases'));
    }

    public function regenerateToken(Request $request, $purchaseId)
    {
        $purchase = $request->user()->purchases()->findOrFail($purchaseId);

        $purchase->update([
            'download_token' => Str::random(64),
            'expires_at' => now()->addHours(config('fraxionfx.download_token_expiry_hours', 24)),
        ]);

        return back()->with('success', 'Download link regenerated successfully.');
    }
}
