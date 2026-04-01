<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

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
            'hero_title_size' => Setting::get('hero_title_size', '8xl'),
            'hero_description_size' => Setting::get('hero_description_size', 'xl'),
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
            'hero_portrait' => 'nullable|image|max:51200',
            'intro_video' => 'nullable|mimes:mp4,webm,mov|max:1048576',
            'site_logo' => 'nullable|image|max:51200',
            'primary_color' => 'nullable|string|regex:/^#[0-9a-fA-F]{6}$/',
            'hero_title_size' => 'nullable|string|in:4xl,5xl,6xl,7xl,8xl,9xl',
            'hero_description_size' => 'nullable|string|in:sm,base,lg,xl,2xl',
        ]);

        Setting::set('hero_title_line1', $request->hero_title_line1);
        Setting::set('hero_title_line2', $request->hero_title_line2);
        Setting::set('hero_title_line3', $request->hero_title_line3);
        Setting::set('hero_description', $request->hero_description);
        if ($request->filled('primary_color')) {
            Setting::set('primary_color', $request->primary_color);
        }

        if ($request->filled('hero_title_size')) {
            Setting::set('hero_title_size', $request->hero_title_size);
        }
        if ($request->filled('hero_description_size')) {
            Setting::set('hero_description_size', $request->hero_description_size);
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

    public function general()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'FraxionFX'),
            'contact_email' => Setting::get('contact_email', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'favicon' => Setting::get('favicon'),
            'auth_heading' => Setting::get('auth_heading', 'Premium 3D Assets & Visual Effects'),
            'auth_description' => Setting::get('auth_description', 'Access exclusive add-ons, tutorials, and professional-grade assets crafted for creators and studios worldwide.'),
            'auth_feature_1' => Setting::get('auth_feature_1', 'Premium Add-ons'),
            'auth_feature_2' => Setting::get('auth_feature_2', 'In-depth Tutorials'),
            'footer_description' => Setting::get('footer_description', ''),
        ];

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'favicon' => 'nullable|image|max:51200',
            'auth_heading' => 'nullable|string|max:255',
            'auth_description' => 'nullable|string|max:500',
            'auth_feature_1' => 'nullable|string|max:100',
            'auth_feature_2' => 'nullable|string|max:100',
            'footer_description' => 'nullable|string|max:200',
        ]);

        Setting::set('site_name', $request->site_name);
        Setting::set('contact_email', $request->contact_email);
        Setting::set('contact_phone', $request->contact_phone);
        Setting::set('auth_heading', $request->auth_heading);
        Setting::set('auth_description', $request->auth_description);
        Setting::set('auth_feature_1', $request->auth_feature_1);
        Setting::set('auth_feature_2', $request->auth_feature_2);
        Setting::set('footer_description', $request->footer_description);

        if ($request->hasFile('favicon')) {
            $old = Setting::get('favicon');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $path);
        }

        return redirect()->route('admin.settings.general')->with('success', 'General settings updated successfully.');
    }

    public function social()
    {
        $settings = [
            'social_twitter' => Setting::get('social_twitter', ''),
            'social_github' => Setting::get('social_github', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_linkedin' => Setting::get('social_linkedin', ''),
            'social_youtube' => Setting::get('social_youtube', ''),
            'social_behance' => Setting::get('social_behance', ''),
            'social_whatsapp' => Setting::get('social_whatsapp', ''),
            'social_facebook' => Setting::get('social_facebook', ''),
            'social_dribbble' => Setting::get('social_dribbble', ''),
            'social_artstation' => Setting::get('social_artstation', ''),
            'social_sketchfab' => Setting::get('social_sketchfab', ''),
        ];

        return view('admin.settings.social', compact('settings'));
    }

    public function updateSocial(Request $request)
    {
        $request->validate([
            'social_twitter' => 'nullable|url|max:500',
            'social_github' => 'nullable|url|max:500',
            'social_instagram' => 'nullable|url|max:500',
            'social_linkedin' => 'nullable|url|max:500',
            'social_youtube' => 'nullable|url|max:500',
            'social_behance' => 'nullable|url|max:500',
            'social_whatsapp' => 'nullable|string|max:500',
            'social_facebook' => 'nullable|url|max:500',
            'social_dribbble' => 'nullable|url|max:500',
            'social_artstation' => 'nullable|url|max:500',
            'social_sketchfab' => 'nullable|url|max:500',
        ]);

        $keys = ['social_twitter', 'social_github', 'social_instagram', 'social_linkedin', 'social_youtube', 'social_behance', 'social_whatsapp', 'social_facebook', 'social_dribbble', 'social_artstation', 'social_sketchfab'];
        foreach ($keys as $key) {
            Setting::set($key, $request->input($key));
        }

        return redirect()->route('admin.settings.social')->with('success', 'Social media links updated successfully.');
    }

    public function about()
    {
        $settings = [
            'about_title' => Setting::get('about_title', 'THE STUDIO'),
            'about_description_1' => Setting::get('about_description_1', 'FraxionFX is a creative studio specializing in cutting-edge 3D visual effects, particle simulations, and digital content creation. Every project is crafted with meticulous attention to detail and a passion for pushing creative boundaries.'),
            'about_description_2' => Setting::get('about_description_2', 'With expertise in Blender, Houdini, and modern rendering pipelines, we deliver stunning visuals that captivate audiences and elevate brands.'),
            'about_stat_1_number' => Setting::get('about_stat_1_number', '50+'),
            'about_stat_1_label' => Setting::get('about_stat_1_label', 'Projects'),
            'about_stat_2_number' => Setting::get('about_stat_2_number', '5+'),
            'about_stat_2_label' => Setting::get('about_stat_2_label', 'Years'),
            'about_stat_3_number' => Setting::get('about_stat_3_number', '100+'),
            'about_stat_3_label' => Setting::get('about_stat_3_label', 'Clients'),
            'about_avatar_name' => Setting::get('about_avatar_name', 'KHAYREDDINE'),
            'about_avatar_title' => Setting::get('about_avatar_title', '3D Artist & FX Designer'),
        ];

        return view('admin.settings.about', compact('settings'));
    }

    public function updateAbout(Request $request)
    {
        $request->validate([
            'about_title' => 'required|string|max:255',
            'about_description_1' => 'required|string|max:2000',
            'about_description_2' => 'nullable|string|max:2000',
            'about_stat_1_number' => 'required|string|max:20',
            'about_stat_1_label' => 'required|string|max:50',
            'about_stat_2_number' => 'required|string|max:20',
            'about_stat_2_label' => 'required|string|max:50',
            'about_stat_3_number' => 'required|string|max:20',
            'about_stat_3_label' => 'required|string|max:50',
            'about_avatar_name' => 'required|string|max:255',
            'about_avatar_title' => 'required|string|max:255',
        ]);

        $keys = ['about_title', 'about_description_1', 'about_description_2', 'about_stat_1_number', 'about_stat_1_label', 'about_stat_2_number', 'about_stat_2_label', 'about_stat_3_number', 'about_stat_3_label', 'about_avatar_name', 'about_avatar_title'];
        foreach ($keys as $key) {
            Setting::set($key, $request->input($key));
        }

        return redirect()->route('admin.settings.about')->with('success', 'About section updated successfully.');
    }

    public function account()
    {
        return view('admin.settings.account');
    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.settings.account')->with('success', 'Account updated successfully.');
    }
}
