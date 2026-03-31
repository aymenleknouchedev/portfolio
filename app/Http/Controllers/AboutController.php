<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class AboutController extends Controller
{
    public function index()
    {
        $about = [
            'title' => Setting::get('about_title', 'THE STUDIO'),
            'description_1' => Setting::get('about_description_1', 'FraxionFX is a creative studio specializing in cutting-edge 3D visual effects, particle simulations, and digital content creation.'),
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

        return view('about', compact('about'));
    }
}
