<?php

namespace App\Http\Controllers;

use App\Http\Requests\PortfolioProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use League\Flysystem\FilesystemException;

class PortfolioProjectController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('portfolio/projects/index', [
            'projects' => request()->user()->portfolioProjects()->latest()->get([
                'id',
                'title',
                'summary',
                'portfolio_review_status',
                'portfolio_reviewed_at',
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('portfolio/projects/form', ['project' => null]);
    }

    public function store(PortfolioProjectRequest $request): RedirectResponse
    {
        $project = new Project($request->safe()->except('cover'));
        $project->user_id = $request->user()->getKey();
        $project->portfolio_owner_id = $request->user()->getKey();
        $project->slug = Str::slug($project->title).'-'.Str::lower(Str::random(8));
        $project->accent = '#2C3E50';
        $project->is_published = false;
        $project->portfolio_review_status = 'pending';

        if ($cover = $request->file('cover')) {
            try {
                $path = $cover->store('portfolios/projects/'.$request->user()->getKey(), 'public');
            } catch (FilesystemException $exception) {
                Log::error('Portfolio project cover upload failed.', ['user_id' => $request->user()->getKey(), 'exception' => $exception]);
                throw ValidationException::withMessages(['cover' => 'The project image could not be uploaded. Please try again.']);
            }

            if (! $path) {
                Log::error('Portfolio project cover upload returned no path.', ['user_id' => $request->user()->getKey()]);
                throw ValidationException::withMessages(['cover' => 'The project image could not be uploaded. Please try again.']);
            }
            $project->cover_image = $path;
        }

        $project->save();
        request()->attributes->set('audit_subject', $project);

        return to_route('portfolio.projects.index')->with('success', 'Project submitted for superadmin review.');
    }

    public function edit(Project $project): Response
    {
        $project = request()->user()->portfolioProjects()->findOrFail($project->getKey());

        return Inertia::render('portfolio/projects/form', ['project' => $project]);
    }

    public function update(PortfolioProjectRequest $request, Project $project): RedirectResponse
    {
        $project = $request->user()->portfolioProjects()->findOrFail($project->getKey());
        $data = $request->safe()->except('cover');
        $cover = $request->file('cover');
        $oldCover = $project->cover_image;

        if ($cover) {
            try {
                $path = $cover->store('portfolios/projects/'.$request->user()->getKey(), 'public');
            } catch (FilesystemException $exception) {
                Log::error('Portfolio project cover upload failed.', ['user_id' => $request->user()->getKey(), 'project_id' => $project->getKey(), 'exception' => $exception]);
                throw ValidationException::withMessages(['cover' => 'The project image could not be uploaded. Please try again.']);
            }

            if (! $path) {
                Log::error('Portfolio project cover upload returned no path.', ['user_id' => $request->user()->getKey(), 'project_id' => $project->getKey()]);
                throw ValidationException::withMessages(['cover' => 'The project image could not be uploaded. Please try again.']);
            }
            $data['cover_image'] = $path;
        }

        $data['portfolio_review_status'] = 'pending';
        $data['portfolio_reviewed_by'] = null;
        $data['portfolio_reviewed_at'] = null;
        $project->update($data);

        if ($cover && $oldCover) {
            $this->deleteCover($oldCover, $project);
        }

        request()->attributes->set('audit_subject', $project);

        return to_route('portfolio.projects.index')->with('success', 'Project changes submitted for review.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project = request()->user()->portfolioProjects()->findOrFail($project->getKey());
        $project->delete();

        if ($project->cover_image) {
            $this->deleteCover($project->cover_image, $project);
        }

        request()->attributes->set('audit_subject', $project);

        return back()->with('success', 'Portfolio project removed.');
    }

    private function deleteCover(string $path, Project $project): void
    {
        try {
            if (! Storage::disk('public')->delete($path)) {
                Log::warning('Portfolio project cover could not be removed.', ['project_id' => $project->getKey(), 'path' => $path]);
            }
        } catch (FilesystemException $exception) {
            Log::warning('Portfolio project cover could not be removed.', ['project_id' => $project->getKey(), 'path' => $path, 'exception' => $exception]);
        }
    }
}
