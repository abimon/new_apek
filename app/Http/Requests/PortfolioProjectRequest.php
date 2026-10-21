<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->portfolio_enabled;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'project_type' => ['required', 'in:product,client'],
            'client' => ['nullable', 'string', 'max:160'],
            'summary' => ['required', 'string', 'max:240'],
            'challenge' => ['required', 'string', 'max:5000'],
            'solution' => ['required', 'string', 'max:5000'],
            'live_url' => ['nullable', 'url', 'max:500'],
            'features' => ['nullable', 'array', 'max:20'],
            'features.*' => ['required', 'string', 'max:160', 'distinct'],
            'cover' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
