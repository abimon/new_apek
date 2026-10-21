<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->portfolio_enabled;
    }

    public function rules(): array
    {
        return [
            'professional_title' => ['required', 'string', 'max:160'],
            'portfolio_summary' => ['required', 'string', 'max:5000'],
            'portfolio_email' => ['nullable', 'email', 'max:255'],
            'portfolio_phone' => ['nullable', 'string', 'max:40'],
            'portfolio_location' => ['nullable', 'string', 'max:160'],
            'portfolio_website' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'portfolio_skills' => ['nullable', 'array', 'max:50'],
            'portfolio_skills.*' => ['required', 'string', 'max:100', 'distinct'],
            'portfolio_experience' => ['nullable', 'array', 'max:20'],
            'portfolio_experience.*.title' => ['required', 'string', 'max:160'],
            'portfolio_experience.*.organization' => ['required', 'string', 'max:160'],
            'portfolio_experience.*.location' => ['nullable', 'string', 'max:160'],
            'portfolio_experience.*.start_date' => ['nullable', 'string', 'max:40'],
            'portfolio_experience.*.end_date' => ['nullable', 'string', 'max:40'],
            'portfolio_experience.*.description' => ['nullable', 'string', 'max:3000'],
            'portfolio_education' => ['nullable', 'array', 'max:20'],
            'portfolio_education.*.qualification' => ['required', 'string', 'max:160'],
            'portfolio_education.*.institution' => ['required', 'string', 'max:160'],
            'portfolio_education.*.location' => ['nullable', 'string', 'max:160'],
            'portfolio_education.*.start_date' => ['nullable', 'string', 'max:40'],
            'portfolio_education.*.end_date' => ['nullable', 'string', 'max:40'],
            'portfolio_education.*.description' => ['nullable', 'string', 'max:2000'],
            'portfolio_certifications' => ['nullable', 'array', 'max:30'],
            'portfolio_certifications.*' => ['required', 'string', 'max:200'],
            'portfolio_languages' => ['nullable', 'array', 'max:20'],
            'portfolio_languages.*' => ['required', 'string', 'max:100', 'distinct'],
            'professional_photo' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'portfolio_is_public' => ['required', 'boolean'],
        ];
    }
}
