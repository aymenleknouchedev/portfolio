<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index(Request $request)
    {
        $reclamations = $request->user()->reclamations()
            ->with('purchase.addon')
            ->latest()
            ->get();

        $purchases = $request->user()->purchases()
            ->with('addon')
            ->where('status', 'completed')
            ->latest()
            ->get();

        return view('client.reclamations.index', compact('reclamations', 'purchases'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
            'purchase_id' => 'nullable|integer',
        ]);

        // Make sure the purchase (if any) belongs to this user
        if (!empty($data['purchase_id'])) {
            $owns = $request->user()->purchases()->whereKey($data['purchase_id'])->exists();
            if (!$owns) {
                $data['purchase_id'] = null;
            }
        } else {
            $data['purchase_id'] = null;
        }

        Reclamation::create([
            'user_id'        => $request->user()->id,
            'purchase_id'    => $data['purchase_id'],
            'subject'        => $data['subject'],
            'message'        => $data['message'],
            'status'         => 'open',
            'is_read_admin'  => false,
            'is_read_client' => true,
        ]);

        return redirect()->route('client.reclamations.index')
            ->with('success', 'Your reclamation has been submitted. We will get back to you soon.');
    }

    public function show(Request $request, Reclamation $reclamation)
    {
        abort_unless($reclamation->user_id === $request->user()->id, 403);

        if (!$reclamation->is_read_client) {
            $reclamation->update(['is_read_client' => true]);
        }

        $reclamation->load('purchase.addon');

        return view('client.reclamations.show', compact('reclamation'));
    }
}
