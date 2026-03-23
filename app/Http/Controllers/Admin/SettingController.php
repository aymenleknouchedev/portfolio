<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function hero()
    {
        $settings = [
            'hero_title_line1' => Setting::get('hero_title_line1', 'KHAYREDDINE'),
            'hero_title_line2' => Setting::get('hero_title_line2', '3D ARTIST'),
            'hero_title_line3' => Setting::get('hero_title_line3', 'FX DESIGNER'),
            'hero_description' => Setting::get('hero_description', 'Crafting cinematic visual effects, immersive 3D environments, and premium digital assets for creators worldwide.'),
            'hero_portrait' => Setting::get('hero_portrait'),
            'intro_video' => Setting::get('intro_video'),
            'site_logo' => Setting::get('site_logo'),
            'primary_color' => Setting::get('primary_color', '#7c3aed'),
        ];

        return view('admin.settings.hero', compact('settings'));
    }

    public function updateHero(Request $request)
    {
        $request->validate([
            'hero_title_line1' => 'required|string|max:255',
            'hero_title_line2' => 'required|string|max:255',
            'hero_title_line3' => 'required|string|max:255',
            'hero_description' => 'required|string|max:1000',
            'hero_portrait' => 'nullable|image|max:5120',
            'intro_video' => 'nullable|mimes:mp4,webm,mov|max:51200',
            'site_logo' => 'nullable|image|max:2048',
            'primary_color' => 'nullable|string|regex:/^#[0-9a-fA-F]{6}$/',
        ]);

        Setting::set('hero_title_line1', $request->hero_title_line1);
        Setting::set('hero_title_line2', $request->hero_title_line2);
        Setting::set('hero_title_line3', $request->hero_title_line3);
        Setting::set('hero_description', $request->hero_description);
        if ($request->filled('primary_color')) {
            Setting::set('primary_color', $request->primary_color);
        }

        if ($request->hasFile('hero_portrait')) {
            $old = Setting::get('hero_portrait');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('hero_portrait')->store('settings', 'public');
            Setting::set('hero_portrait', $path);
        }

        if ($request->hasFile('intro_video')) {
            $old = Setting::get('intro_video');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('intro_video')->store('settings', 'public');
            Setting::set('intro_video', $path);
        }

        if ($request->hasFile('site_logo')) {
            $old = Setting::get('site_logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path);
        }

        return redirect()->route('admin.settings.hero')->with('success', 'Hero settings updated successfully.');
    }
}
