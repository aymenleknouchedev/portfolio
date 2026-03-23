<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Article;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectCategory')->published()->latest('published_at')->take(3)->get();
        $featuredAddons = Addon::where('is_featured', true)->with('category')->take(3)->get();
        $services = Service::where('is_active', true)->get();
        $articles = Article::published()->latest('published_at')->take(3)->get();

        // Hero settings
        $hero = [
            'title_line1' => Setting::get('hero_title_line1', 'KHAYREDDINE'),
            'title_line2' => Setting::get('hero_title_line2', '3D ARTIST'),
            'title_line3' => Setting::get('hero_title_line3', 'FX DESIGNER'),
            'description' => Setting::get('hero_description', 'Crafting cinematic visual effects, immersive 3D environments, and premium digital assets for creators worldwide.'),
            'portrait' => Setting::get('hero_portrait'),
            'intro_video' => Setting::get('intro_video'),
        ];

        return view('home', compact('projects', 'featuredAddons', 'services', 'articles', 'hero'));
    }
}