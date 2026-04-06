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
        $projects = Project::latest()->get();
        $featuredProjects = Project::where('is_featured', true)->orderBy('sort_order')->orderBy('published_at', 'desc')->get();
        return view('admin.projects.index', compact('projects', 'featuredProjects'));
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
            'hero_image' => 'nullable|image|max:51200',
            'hero_video' => 'nullable|url',
            'url' => 'nullable|url|max:500',
            'gallery.*' => 'nullable',
            'software_used' => 'nullable|string',
            'process_steps' => 'nullable|string',
            'published_at' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
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
            'hero_image' => 'nullable|image|max:51200',
            'hero_video' => 'nullable|url',
            'url' => 'nullable|url|max:500',
            'gallery.*' => 'nullable|image|max:51200',
            'software_used' => 'nullable|string',
            'process_steps' => 'nullable|string',
            'published_at' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
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

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:projects,id',
        ]);

        foreach ($request->order as $index => $id) {
            Project::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm|max:51200',
        ]);

        $path = $request->file('file')->store('projects/content', 'public');

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }

    public function downloadImages(Project $project)
    {
        $images = [];

        if ($project->hero_image && Storage::disk('public')->exists($project->hero_image)) {
            $images['hero_' . basename($project->hero_image)] = Storage::disk('public')->path($project->hero_image);
        }

        if ($project->gallery) {
            foreach ($project->gallery as $index => $img) {
                if (Storage::disk('public')->exists($img)) {
                    $images['gallery_' . ($index + 1) . '_' . basename($img)] = Storage::disk('public')->path($img);
                }
            }
        }

        if (empty($images)) {
            return back()->with('error', 'No images found for this project.');
        }

        $zipName = Str::slug($project->title) . '-images.zip';
        $zipPath = storage_path('app/temp/' . $zipName);

        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach ($images as $name => $path) {
            $zip->addFile($path, $name);
        }
        $zip->close();

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
}