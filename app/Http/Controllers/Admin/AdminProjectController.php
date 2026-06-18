<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class AdminProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index', ['projects' => Project::orderBy('created_at', 'desc')->get()]);
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug',
            'description' => 'required|string',
            'technologies' => 'nullable|string',
            'category' => 'required|string|max:120',
            'image' => 'nullable|url',
            'github_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'featured' => 'nullable|boolean',
        ]);

        $validated['technologies'] = collect(explode(',', $validated['technologies'] ?? ''))->map(fn($item) => trim($item))->filter()->values()->all();
        $validated['featured'] = $request->boolean('featured');

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', ['project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "required|string|max:255|unique:projects,slug,{$project->id}",
            'description' => 'required|string',
            'technologies' => 'nullable|string',
            'category' => 'required|string|max:120',
            'image' => 'nullable|url',
            'github_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'featured' => 'nullable|boolean',
        ]);

        $validated['technologies'] = collect(explode(',', $validated['technologies'] ?? ''))->map(fn($item) => trim($item))->filter()->values()->all();
        $validated['featured'] = $request->boolean('featured');

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project removed successfully.');
    }
}
