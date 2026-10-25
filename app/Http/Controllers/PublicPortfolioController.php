<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PublicPortfolioController extends Controller
{
    public function show(string $slug): Response
    {
        $user = $this->publicUser($slug);

        return Inertia::render('portfolio/public', [
            'canonicalUrl' => route('portfolio.public', $user->portfolio_slug),
            'profile' => $user->only([
                'name',
                'portfolio_slug',
                'professional_title',
                'portfolio_summary',
                'portfolio_email',
                'portfolio_phone',
                'portfolio_location',
                'portfolio_website',
                'linkedin_url',
                'github_url',
                'portfolio_skills',
                'portfolio_experience',
                'portfolio_education',
                'portfolio_certifications',
                'portfolio_languages',
            ]) + [
                'professional_photo_url' => $user->professional_photo
                    ? Storage::disk('public')->url($user->professional_photo)
                    : null,
            ],
            'projects' => $user->portfolioProjects()
                ->where('portfolio_review_status', 'approved')
                ->with(['categories:id,name,slug', 'techStacks:id,name,slug'])
                ->latest()
                ->get()
                ->map(fn ($project) => [
                    'title' => $project->title,
                    'project_type' => $project->project_type,
                    'summary' => $project->summary,
                    'challenge' => $project->challenge,
                    'solution' => $project->solution,
                    'cover_image_url' => $project->cover_image
                        ? Storage::disk('public')->url($project->cover_image)
                        : null,
                    'live_url' => $project->live_url,
                    'features' => $project->features ?? [],
                    'categories' => $project->categories->pluck('name'),
                    'tech_stacks' => $project->techStacks->pluck('name'),
                ]),
            'cvUrl' => route('portfolio.cv', $user->portfolio_slug),
        ]);
    }

    public function cv(string $slug): HttpResponse
    {
        $user = $this->publicUser($slug);
        $projects = $user->portfolioProjects()
            ->where('portfolio_review_status', 'approved')
            ->latest()
            ->get(['title', 'project_type', 'summary', 'features', 'live_url']);

        $photoData = null;
        if ($user->professional_photo && Storage::disk('public')->exists($user->professional_photo)) {
            $mime = Storage::disk('public')->mimeType($user->professional_photo);
            $photoData = 'data:'.$mime.';base64,'.base64_encode(Storage::disk('public')->get($user->professional_photo));
        }

        return Pdf::loadView('portfolio.cv-pdf', [
            'profile' => $user,
            'projects' => $projects,
            'photoData' => $photoData,
        ])->setPaper('a4')->download($user->portfolio_slug.'-cv.pdf');
    }

    private function publicUser(string $slug): User
    {
        return User::query()
            ->where('portfolio_slug', $slug)
            ->where('portfolio_enabled', true)
            ->where('portfolio_is_public', true)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
