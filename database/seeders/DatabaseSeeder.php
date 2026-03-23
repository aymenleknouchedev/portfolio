<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\AddonCategory;
use App\Models\Article;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@fraxionfx.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Client User',
            'email' => 'client@fraxionfx.com',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);

        // Addon Categories
        $vfxCategory = AddonCategory::create([
            'name' => 'VFX Assets',
            'slug' => 'vfx-assets',
            'description' => 'High-quality visual effects assets for Blender and other 3D software.',
            'is_active' => true,
        ]);

        $simulationCategory = AddonCategory::create([
            'name' => 'Simulation Presets',
            'slug' => 'simulation-presets',
            'description' => 'Ready-to-use simulation presets for fluid, fire, smoke and more.',
            'is_active' => true,
        ]);

        // Addons
        Addon::create([
            'category_id' => $vfxCategory->id,
            'name' => 'Cinematic Particle System',
            'slug' => 'cinematic-particle-system',
            'description' => 'A comprehensive particle system pack with over 50 presets for cinematic VFX in Blender. Includes dust, sparks, embers, magic effects, and more.',
            'price' => 29.99,
            'demo_video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'features' => ['50+ particle presets', 'One-click setup', 'Blender 3.6+ compatible', 'Customizable parameters', 'Video tutorials included'],
            'screenshots' => ['/images/addon1-1.jpg', '/images/addon1-2.jpg', '/images/addon1-3.jpg'],
            'is_featured' => true,
            'file_path' => 'addons/cinematic-particles.zip',
        ]);

        Addon::create([
            'category_id' => $vfxCategory->id,
            'name' => 'Procedural Shader Pack',
            'slug' => 'procedural-shader-pack',
            'description' => 'Advanced procedural shaders for creating stunning materials without textures. Perfect for sci-fi, fantasy, and abstract art.',
            'price' => 19.99,
            'demo_video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'features' => ['30+ procedural shaders', 'No textures needed', 'Fully node-based', 'Cycles & EEVEE support'],
            'screenshots' => ['/images/addon2-1.jpg', '/images/addon2-2.jpg'],
            'is_featured' => true,
            'file_path' => 'addons/procedural-shaders.zip',
        ]);

        Addon::create([
            'category_id' => $simulationCategory->id,
            'name' => 'Fluid Dynamics Pro',
            'slug' => 'fluid-dynamics-pro',
            'description' => 'Professional fluid simulation presets for realistic water, lava, and liquid effects in Blender.',
            'price' => 39.99,
            'demo_video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'features' => ['20+ fluid presets', 'Physics-based simulation', 'Ocean & river templates', 'High-res output ready', 'Batch rendering support'],
            'screenshots' => ['/images/addon3-1.jpg', '/images/addon3-2.jpg'],
            'is_featured' => true,
            'file_path' => 'addons/fluid-dynamics.zip',
        ]);

        // Project Categories
        $cat3d = ProjectCategory::create(['name' => '3D Animation', 'slug' => '3d-animation', 'is_active' => true]);
        $catVfx = ProjectCategory::create(['name' => 'VFX', 'slug' => 'vfx', 'is_active' => true]);
        $catEnv = ProjectCategory::create(['name' => 'Environment Design', 'slug' => 'environment-design', 'is_active' => true]);
        $catChar = ProjectCategory::create(['name' => 'Character Design', 'slug' => 'character-design', 'is_active' => true]);

        // Projects
        Project::create([
            'title' => 'Nebula Core — Sci-Fi Short Film',
            'slug' => 'nebula-core',
            'description' => 'A 3-minute sci-fi short film featuring advanced particle simulations, volumetric lighting, and procedural environments. Created entirely in Blender.',
            'hero_image' => 'images/project1.jpg',
            'hero_video' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'software_used' => ['Blender', 'After Effects', 'DaVinci Resolve'],
            'process_steps' => ['Concept & storyboarding', 'Environment modeling', 'Particle FX setup', 'Lighting & rendering', 'Post-production & compositing'],
            'category_id' => $cat3d->id,
            'published_at' => now()->subDays(5),
        ]);

        Project::create([
            'title' => 'Volcanic Eruption — FX Breakdown',
            'slug' => 'volcanic-eruption',
            'description' => 'A complete breakdown of a realistic volcanic eruption simulation using fluid dynamics, particle systems, and volumetric smoke.',
            'hero_image' => 'images/project2.jpg',
            'software_used' => ['Blender', 'Houdini'],
            'process_steps' => ['Reference gathering', 'Smoke simulation', 'Lava flow dynamics', 'Debris particles', 'Final compositing'],
            'category_id' => $catVfx->id,
            'published_at' => now()->subDays(10),
        ]);

        Project::create([
            'title' => 'Crystal Forest — Environment Design',
            'slug' => 'crystal-forest',
            'description' => 'A mystical crystal forest environment featuring procedural geometry, custom shaders, and atmospheric lighting.',
            'hero_image' => 'images/project3.jpg',
            'software_used' => ['Blender', 'Substance Painter'],
            'process_steps' => ['Procedural modeling', 'Shader development', 'Scene layout', 'Lighting design', 'Final render'],
            'category_id' => $catEnv->id,
            'published_at' => now()->subDays(15),
        ]);

        Project::create([
            'title' => 'Mech Warrior — Character Design',
            'slug' => 'mech-warrior',
            'description' => 'Hard-surface mechanical warrior character design with detailed armor, weapons, and rigging for animation.',
            'hero_image' => 'images/project4.jpg',
            'software_used' => ['Blender', 'ZBrush', 'Substance Painter'],
            'process_steps' => ['Concept sketching', 'High-poly sculpting', 'Retopology', 'UV & texturing', 'Rigging & posing'],
            'category_id' => $catChar->id,
            'published_at' => now()->subDays(20),
        ]);

        // Services
        Service::create([
            'title' => '3D Animation & VFX',
            'slug' => '3d-animation-vfx',
            'description' => 'Custom 3D animations and visual effects for films, commercials, and social media. From concept to final render.',
            'price_range' => '$500 - $5,000',
            'example_image' => 'images/service1.jpg',
            'is_active' => true,
        ]);

        Service::create([
            'title' => 'Product Visualization',
            'slug' => 'product-visualization',
            'description' => 'Photorealistic 3D product renders for marketing, e-commerce, and presentations. Make your products stand out.',
            'price_range' => '$200 - $2,000',
            'example_image' => 'images/service2.jpg',
            'is_active' => true,
        ]);

        // Articles
        Article::create([
            'title' => 'Getting Started with Blender Particle Systems',
            'slug' => 'getting-started-blender-particles',
            'excerpt' => 'Learn the fundamentals of Blender\'s particle system and create your first cinematic particle effect.',
            'content' => "## Introduction\n\nBlender's particle system is one of the most powerful tools for creating stunning visual effects. In this guide, we'll walk through the basics.\n\n## Setting Up Your First Emitter\n\nStart by selecting your object and navigating to the Particle Properties panel. Click the **+** button to add a new particle system.\n\n## Key Parameters\n\n- **Emission**: Controls how many particles are created\n- **Velocity**: Determines initial particle speed and direction\n- **Physics**: Simulates real-world forces like gravity\n- **Render**: How particles appear in the final output\n\n## Tips & Tricks\n\n1. Use **Force Fields** to control particle flow\n2. Combine with **Volume Scatter** for atmospheric effects\n3. Bake your simulations for consistent results\n\n## Conclusion\n\nMastering particle systems opens up endless creative possibilities. Start experimenting today!",
            'hero_image' => 'images/article1.jpg',
            'is_published' => true,
            'published_at' => now()->subDays(3),
        ]);

        Article::create([
            'title' => 'Advanced Fluid Simulation Techniques',
            'slug' => 'advanced-fluid-simulation',
            'excerpt' => 'Master advanced fluid simulation techniques in Blender for realistic water, lava, and liquid effects.',
            'content' => "## Introduction\n\nFluid simulations can add incredible realism to your 3D scenes. This article covers advanced techniques.\n\n## Domain Setup\n\nThe domain object defines the space where your simulation runs. Size it appropriately for best results.\n\n## Resolution Settings\n\n- **Division**: Higher values = more detail but longer bake times\n- **Time Scale**: Control simulation speed\n- **Viscosity**: from water-thin to honey-thick\n\n## Mesh Smoothing\n\nUse the **Mesh** display type with smooth subdivision for the final render.\n\n## Optimization Tips\n\n1. Start with low resolution to test motion\n2. Use adaptive domain to save memory\n3. Cache to disk for large simulations\n\n## Conclusion\n\nWith practice, fluid simulations become an invaluable tool in your VFX arsenal.",
            'hero_image' => 'images/article2.jpg',
            'is_published' => true,
            'published_at' => now()->subDays(7),
        ]);
    }
}
