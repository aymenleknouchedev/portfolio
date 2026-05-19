<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index()
    {
        $reclamations = Reclamation::with(['user', 'purchase.addon'])
            ->latest()
            ->paginate(20);

        $unreadCount = Reclamation::where('is_read_admin', false)->count();

        return view('admin.reclamations.index', compact('reclamations', 'unreadCount'));
    }

    public function show(Reclamation $reclamation)
    {
        if (!$reclamation->is_read_admin) {
            $reclamation->update(['is_read_admin' => true]);
        }

        $reclamation->load(['user', 'purchase.addon']);

        return view('admin.reclamations.show', compact('reclamation'));
    }

    public function reply(Request $request, Reclamation $reclamation)
    {
        $data = $request->validate([
            'admin_reply' => 'required|string|max:5000',
            'status'      => 'required|in:open,in_progress,resolved,closed',
        ]);

        $reclamation->update([
            'admin_reply'    => $data['admin_reply'],
            'status'         => $data['status'],
            'replied_at'     => now(),
            'is_read_client' => false,
            'is_read_admin'  => true,
        ]);

        return redirect()->route('admin.reclamations.show', $reclamation)
            ->with('success', 'Reply sent to client.');
    }

    public function updateStatus(Request $request, Reclamation $reclamation)
    {
        $data = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $reclamation->update(['status' => $data['status']]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(Reclamation $reclamation)
    {
        $reclamation->delete();

        return redirect()->route('admin.reclamations.index')
            ->with('success', 'Reclamation deleted.');
    }
}
