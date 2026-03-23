<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProjectCategory::where('is_active', true)->orderBy('name')->get();

        $projects = Project::with('projectCategory')->published()
            ->when($request->category, fn($q, $slug) => $q->whereHas('projectCategory', fn($q) => $q->where('slug', $slug)))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('projects.index', compact('projects', 'categories'));
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }
}
