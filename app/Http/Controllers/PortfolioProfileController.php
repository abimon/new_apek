<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePortfolioProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use League\Flysystem\FilesystemException;

class PortfolioProfileController extends Controller
{
    public function edit(): Response
    {
        $user = request()->user();

        return Inertia::render('portfolio/profile', [
            'profile' => $user->only([
                'name',
                'portfolio_slug',
                'portfolio_is_public',
                'professional_title',
                'portfolio_summary',
                'portfolio_email',
                'portfolio_phone',
                'portfolio_location',
                'portfolio_website',
                'linkedin_url',
                'github_url',
                'professional_photo',
                'portfolio_skills',
                'portfolio_experience',
                'portfolio_education',
                'portfolio_certifications',
                'portfolio_languages',
            ]) + [
                'professional_photo_url' => $user->professional_photo
                    ? Storage::disk('public')->url($user->professional_photo)
                    : null,
                'public_url' => route('portfolio.public', $user->portfolio_slug),
            ],
            'projects' => $user->portfolioProjects()
                ->latest()
                ->get(['id', 'title', 'summary', 'portfolio_review_status', 'portfolio_reviewed_at']),
        ]);
    }

    public function update(UpdatePortfolioProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $photo = $request->file('professional_photo');
        unset($data['professional_photo']);

        if ($photo) {
            try {
                $path = $photo->store('portfolios/photos/'.$user->getKey(), 'public');
            } catch (FilesystemException $exception) {
                Log::error('Portfolio photo upload failed.', ['user_id' => $user->getKey(), 'exception' => $exception]);
                throw ValidationException::withMessages(['professional_photo' => 'The photo could not be uploaded. Please try again.']);
            }

            if (! $path) {
                Log::error('Portfolio photo upload returned no path.', ['user_id' => $user->getKey()]);
                throw ValidationException::withMessages(['professional_photo' => 'The photo could not be uploaded. Please try again.']);
            }

            $oldPhoto = $user->professional_photo;
            $data['professional_photo'] = $path;
        }

        $user->fill($data)->save();

        if ($photo && $oldPhoto) {
            try {
                if (! Storage::disk('public')->delete($oldPhoto)) {
                    Log::warning('Previous portfolio photo could not be removed.', ['user_id' => $user->getKey(), 'path' => $oldPhoto]);
                }
            } catch (FilesystemException $exception) {
                Log::warning('Previous portfolio photo could not be removed.', ['user_id' => $user->getKey(), 'path' => $oldPhoto, 'exception' => $exception]);
            }
        }

        return back()->with('success', 'Portfolio profile updated.');
    }
}
