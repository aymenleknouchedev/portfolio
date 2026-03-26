<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('sort_order')->latest()->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = ProjectCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.projects.form', compact('categories'));
    }

    public function store(Request $request)
    {
        \Log::info('ProjectController@store hit', ['all' => $request->except('gallery', 'hero_image')]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:project_categories,id',
            'hero_image' => 'nullable|image|max:5120',
            'hero_video' => 'nullable|url',
            'url' => 'nullable|url|max:500',
            'gallery.*' => 'nullable',
            'software_used' => 'nullable|string',
            'process_steps' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['software_used'] = $request->software_used ? json_decode($request->software_used, true) : [];
        $validated['process_steps'] = $request->process_steps ? json_decode($request->process_steps, true) : [];

        if ($request->hasFile('hero_image')) {
            $validated['hero_image'] = $request->file('hero_image')->store('projects', 'public');
        }

        // Handle gallery uploads
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('projects/gallery', 'public');
            }
        }
        $validated['gallery'] = $galleryPaths;

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $categories = ProjectCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.projects.form', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:project_categories,id',
            'hero_image' => 'nullable|image|max:5120',
            'hero_video' => 'nullable|url',
            'url' => 'nullable|url|max:500',
            'gallery.*' => 'nullable|image|max:5120',
            'software_used' => 'nullable|string',
            'process_steps' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['software_used'] = $request->software_used ? json_decode($request->software_used, true) : [];
        $validated['process_steps'] = $request->process_steps ? json_decode($request->process_steps, true) : [];

        if ($request->hasFile('hero_image')) {
            $validated['hero_image'] = $request->file('hero_image')->store('projects', 'public');
        }

        // Handle gallery: keep existing + add new
        $existingGallery = $project->gallery ?? [];

        // Remove deleted images
        $removedImages = json_decode($request->input('removed_gallery', '[]'), true) ?? [];
        foreach ($removedImages as $img) {
            Storage::disk('public')->delete($img);
            $existingGallery = array_values(array_filter($existingGallery, fn($g) => $g !== $img));
        }

        // Add new uploads
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $existingGallery[] = $image->store('projects/gallery', 'public');
            }
        }
        $validated['gallery'] = array_values($existingGallery);

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        // Clean up gallery images
        if ($project->gallery) {
            foreach ($project->gallery as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}