<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'superadmin'], true);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'alpha_dash', 'max:180', 'unique:projects,slug,'.$this->route('project')?->id],
            'client' => ['nullable', 'string', 'max:160'],
            'project_type' => ['required', 'in:product,client'],
            'summary' => ['required', 'string', 'max:240'],
            'challenge' => ['required', 'string', 'max:5000'],
            'solution' => ['required', 'string', 'max:5000'],
            'live_url' => ['nullable', 'url', 'max:500'],
            'cover' => ['nullable', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'accent' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'features' => ['nullable', 'array', 'max:20'],
            'features.*' => ['required', 'string', 'max:160', 'distinct'],
            'outcomes' => ['nullable', 'array', 'max:8'],
            'outcomes.*' => ['string', 'max:160'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'tech_stack_ids' => ['nullable', 'array'],
            'tech_stack_ids.*' => ['integer', 'exists:tech_stacks,id'],
            'owner_involvement' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
        ];
    }
}