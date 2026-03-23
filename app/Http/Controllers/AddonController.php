<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\AddonCategory;

class AddonController extends Controller
{
    public function index()
    {
        $categories = AddonCategory::where('is_active', true)->get();
        $addons = Addon::with('category')->latest()->get();
        return view('addons.index', compact('addons', 'categories'));
    }

    public function show(Addon $addon)
    {
        $addon->load('category');
        $relatedAddons = Addon::where('category_id', $addon->category_id)
            ->where('id', '!=', $addon->id)
            ->take(3)->get();
        return view('addons.show', compact('addon', 'relatedAddons'));
    }
}
