<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    /**
     * Refresh a license key for a client:
     *  - Generates a brand-new key (old key is invalidated).
     *  - Clears machine_id so the client can activate on a new machine.
     *  - Status is reset to 'active'.
     */
    public function refresh(License $license): RedirectResponse
    {
        $license->update([
            'key'        => Str::upper(Str::random(32)),
            'machine_id' => null,
            'status'     => 'active',
        ]);

        return back()->with('success', "License key for {$license->addon->name} has been refreshed.");
    }
}
