<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['user', 'addon'])->latest()->paginate(20);
        return view('admin.purchases.index', compact('purchases'));
    }
}
