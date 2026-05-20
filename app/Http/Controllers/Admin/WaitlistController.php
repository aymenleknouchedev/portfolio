<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use Inertia\Inertia;

class WaitlistController extends Controller
{
    public function index()
    {
        $entries = Waitlist::latest()->paginate(20);
        return Inertia::render('Waitlist/Index', compact('entries'));
    }
}
