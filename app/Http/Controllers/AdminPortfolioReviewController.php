<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminPortfolioReviewController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/portfolio-projects/index', [
            'projects' => Project::query()
                ->where('portfolio_review_status', 'pending')
                ->with(['portfolioOwner:id,name,email', 'categories:id,name', 'techStacks:id,name'])
                ->latest()
                ->paginate(25)
                ->through(fn (Project $project) => [
                    'id' => $project->id,
                    'title' => $project->title,
                    'project_type' => $project->project_type,
                    'summary' => $project->summary,
                    'challenge' => $project->challenge,
                    'solution' => $project->solution,
                    'client' => $project->client,
                    'cover_image_url' => $project->cover_image
                        ? Storage::disk('public')->url($project->cover_image)
                        : null,
                    'live_url' => $project->live_url,
                    'features' => $project->features,
                    'owner' => $project->portfolioOwner?->only(['id', 'name', 'email']),
                    'categories' => $project->categories->pluck('name'),
                    'tech_stacks' => $project->techStacks->pluck('name'),
                    'created_at' => $project->created_at,
                ]),
        ]);
    }

    public function approve(Project $project): RedirectResponse
    {
        abort_unless($project->portfolio_owner_id && $project->portfolio_review_status === 'pending', 404);

        $project->update([
            'portfolio_review_status' => 'approved',
            'portfolio_reviewed_by' => request()->user()->getKey(),
            'portfolio_reviewed_at' => now(),
        ]);
        request()->attributes->set('audit_subject', $project);

        return back()->with('success', 'Project approved for the owner’s public portfolio.');
    }

    public function reject(Project $project): RedirectResponse
    {
        abort_unless($project->portfolio_owner_id && $project->portfolio_review_status === 'pending', 404);

        $project->update([
            'portfolio_review_status' => 'rejected',
            'portfolio_reviewed_by' => request()->user()->getKey(),
            'portfolio_reviewed_at' => now(),
        ]);
        request()->attributes->set('audit_subject', $project);

        return back()->with('success', 'Project rejected. The owner may edit and resubmit it for review.');
    }
}
