<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\AddonCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::with('category')->latest()->get();
        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        $categories = AddonCategory::where('is_active', true)->get();
        return view('admin.addons.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:addon_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:51200',
            'price' => 'required|numeric|min:0',
            'demo_video_url' => 'nullable|string|max:500',
            'features' => 'nullable|string',
            'is_featured' => 'boolean',
            'requires_license' => 'boolean',
            'license_price' => 'nullable|numeric|min:0',
            'file' => 'nullable|file|max:102400',
            'download_url' => 'nullable|url|max:1000',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['requires_license'] = $request->has('requires_license');
        $validated['license_price'] = $request->filled('license_price') ? $request->license_price : null;
        $validated['features'] = $request->features ? json_decode($request->features, true) : [];
        $validated['license_tiers'] = $this->parseLicenseTiers($request->input('license_tiers'));
        unset($validated['download_url']);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('addons/covers', 'public');
        }

        if ($request->hasFile('screenshots')) {
            $screenshots = [];
            foreach ($request->file('screenshots') as $screenshot) {
                $screenshots[] = $screenshot->store('screenshots', 'public');
            }
            $validated['screenshots'] = $screenshots;
        }

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('addons', 'local');
        } elseif ($request->filled('download_url')) {
            $validated['file_path'] = $request->download_url;
        }

        Addon::create($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on created successfully.');
    }

    public function edit(Addon $addon)
    {
        $categories = AddonCategory::where('is_active', true)->get();
        return view('admin.addons.form', compact('addon', 'categories'));
    }

    public function update(Request $request, Addon $addon)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:addon_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:51200',
            'price' => 'required|numeric|min:0',
            'demo_video_url' => 'nullable|string|max:500',
            'features' => 'nullable|string',
            'is_featured' => 'boolean',
            'requires_license' => 'boolean',
            'license_price' => 'nullable|numeric|min:0',
            'file' => 'nullable|file|max:102400',
            'download_url' => 'nullable|url|max:1000',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['requires_license'] = $request->has('requires_license');
        $validated['license_price'] = $request->filled('license_price') ? $request->license_price : null;
        $validated['features'] = $request->features ? json_decode($request->features, true) : [];
        $validated['license_tiers'] = $this->parseLicenseTiers($request->input('license_tiers'));
        unset($validated['download_url']);

        if ($request->hasFile('cover_image')) {
            if ($addon->cover_image) {
                Storage::disk('public')->delete($addon->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('addons/covers', 'public');
        }

        if ($request->hasFile('screenshots')) {
            // Start with existing screenshots, remove any flagged for deletion
            $existingScreenshots = $addon->screenshots ?? [];
            if ($request->filled('removed_screenshots')) {
                $removedPaths = json_decode($request->removed_screenshots, true) ?? [];
                foreach ($removedPaths as $removedPath) {
                    Storage::disk('public')->delete($removedPath);
                }
                $existingScreenshots = array_values(array_filter($existingScreenshots, fn($s) => !in_array($s, $removedPaths)));
            }
            foreach ($request->file('screenshots') as $screenshot) {
                $existingScreenshots[] = $screenshot->store('screenshots', 'public');
            }
            $validated['screenshots'] = $existingScreenshots;
        } elseif ($request->filled('removed_screenshots')) {
            $existingScreenshots = $addon->screenshots ?? [];
            $removedPaths = json_decode($request->removed_screenshots, true) ?? [];
            foreach ($removedPaths as $removedPath) {
                Storage::disk('public')->delete($removedPath);
            }
            $validated['screenshots'] = array_values(array_filter($existingScreenshots, fn($s) => !in_array($s, $removedPaths)));
        }

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('addons', 'local');
        } elseif ($request->filled('download_url')) {
            $validated['file_path'] = $request->download_url;
        }

        $addon->update($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated successfully.');
    }

    public function destroy(Addon $addon)
    {
        $addon->delete();
        return redirect()->route('admin.addons.index')->with('success', 'Add-on deleted successfully.');
    }

    private function parseLicenseTiers(?string $json): array
    {
        if (!$json) return [];
        $decoded = json_decode($json, true);
        if (!is_array($decoded)) return [];
        return array_values(array_filter(array_map(function ($tier) {
            $label = trim($tier['label'] ?? '');
            $quantity = isset($tier['quantity']) ? max(1, (int) $tier['quantity']) : 1;
            $price = isset($tier['price']) ? (float) $tier['price'] : null;
            if ($label === '' || $price === null) return null;
            return ['label' => $label, 'quantity' => $quantity, 'price' => $price];
        }, $decoded)));
    }
}
