<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\TechStack;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use League\Flysystem\FilesystemException;
use Inertia\Inertia;
use Inertia\Response;

class AdminProjectController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/projects/index', ['projects' => Project::with('categories')->latest()->get()]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/projects/form', ['project' => null, ...$this->options()]);
    }

    public function store(AdminProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $cover = $request->file('cover');
        unset($validated['cover']);

        $validated['user_id'] = Auth::id();
        $validated['slug'] = $validated['slug'] ?? str($validated['title'])->slug();
        if ($validated['project_type'] === 'product') {
            $validated['client'] = null;
        }
        if ($cover) {
            $validated['cover_image'] = $this->storeCover($cover);
        }

        $project = Project::create($validated);
        $project->categories()->sync($request->validated('category_ids', []));
        $project->techStacks()->sync($request->validated('tech_stack_ids', []));
        request()->attributes->set('audit_subject', $project);

        return to_route('admin.projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project): Response
    {
        return Inertia::render('admin/projects/form', ['project' => $project->load(['categories', 'techStacks']), ...$this->options()]);
    }

    public function update(AdminProjectRequest $request, Project $project): RedirectResponse
    {
        $validated = $request->validated();
        $cover = $request->file('cover');
        unset($validated['cover'], $validated['category_ids'], $validated['tech_stack_ids']);

        $oldCover = $project->cover_image;
        if ($validated['project_type'] === 'product') {
            $validated['client'] = null;
        }
        if ($cover) {
            $validated['cover_image'] = $this->storeCover($cover);
        }

        $project->update($validated);
        $project->categories()->sync($request->validated('category_ids', []));
        $project->techStacks()->sync($request->validated('tech_stack_ids', []));

        if ($cover && $oldCover) {
            Storage::disk('public')->delete($oldCover);
        }

        return to_route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        if ($project->cover_image) {
            Storage::disk('public')->delete($project->cover_image);
        }

        $project->delete();

        return back()->with('success', 'Project removed.');
    }

    private function options(): array
    {
        return ['categories' => Category::orderBy('name')->get(['id', 'name']), 'techStacks' => TechStack::orderBy('name')->get(['id', 'name'])];
    }

    private function storeCover(UploadedFile $cover): string
    {
        try {
            $path = $cover->store('projects/covers', 'public');
        } catch (FilesystemException $exception) {
            Log::error('Project cover image upload failed.', [
                'exception' => $exception,
                'original_name' => $cover->getClientOriginalName(),
            ]);

            throw ValidationException::withMessages([
                'cover' => 'The cover image could not be saved. Please try again.',
            ]);
        }

        if (! $path) {
            Log::error('Project cover image upload failed because the storage disk returned no path.', [
                'original_name' => $cover->getClientOriginalName(),
            ]);

            throw ValidationException::withMessages([
                'cover' => 'The cover image could not be saved. Please try again.',
            ]);
        }

        return $path;
    }
}
