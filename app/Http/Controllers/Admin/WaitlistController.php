<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;

class WaitlistController extends Controller
{
    public function index()
    {
        $entries = Waitlist::latest()->paginate(20);
        return view('admin.waitlist.index', compact('entries'));
    }
}
