<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectCategory')->published()->where('is_featured', true)->latest('published_at')->take(6)->get();
        $featuredAddons = Addon::where('is_featured', true)->with('category')->take(3)->get();
        $services = Service::where('is_active', true)->get();
        $articles = Article::published()->latest('published_at')->take(3)->get();
        $brands = Brand::active()->orderBy('sort_order')->get();

        // Hero settings
        $hero = [
            'title_line1' => Setting::get('hero_title_line1', 'KHAYREDDINE'),
            'title_line2' => Setting::get('hero_title_line2', '3D ARTIST'),
            'title_line3' => Setting::get('hero_title_line3', 'FX DESIGNER'),
            'description' => Setting::get('hero_description', 'Crafting cinematic visual effects, immersive 3D environments, and premium digital assets for creators worldwide.'),
            'portrait' => Setting::get('hero_portrait'),
            'intro_video' => Setting::get('intro_video'),
            'title_size' => Setting::get('hero_title_size', '8xl'),
            'description_size' => Setting::get('hero_description_size', 'xl'),
        ];

        // About section settings
        $about = [
            'title' => Setting::get('about_title', 'THE STUDIO'),
            'description_1' => Setting::get('about_description_1', 'FraxionFX is a creative studio specializing in cutting-edge 3D visual effects, particle simulations, and digital content creation. Every project is crafted with meticulous attention to detail and a passion for pushing creative boundaries.'),
            'description_2' => Setting::get('about_description_2', 'With expertise in Blender, Houdini, and modern rendering pipelines, we deliver stunning visuals that captivate audiences and elevate brands.'),
            'stat_1_number' => Setting::get('about_stat_1_number', '50+'),
            'stat_1_label' => Setting::get('about_stat_1_label', 'Projects'),
            'stat_2_number' => Setting::get('about_stat_2_number', '5+'),
            'stat_2_label' => Setting::get('about_stat_2_label', 'Years'),
            'stat_3_number' => Setting::get('about_stat_3_number', '100+'),
            'stat_3_label' => Setting::get('about_stat_3_label', 'Clients'),
            'avatar_name' => Setting::get('about_avatar_name', 'KHAYREDDINE'),
            'avatar_title' => Setting::get('about_avatar_title', '3D Artist & FX Designer'),
        ];

        return view('home', compact('projects', 'featuredAddons', 'services', 'articles', 'hero', 'about', 'brands'));
    }
}