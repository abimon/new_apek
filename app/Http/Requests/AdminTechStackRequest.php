<?php

namespace App\Http\Requests;

use App\Models\TechStack;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminTechStackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $techStack = $this->route('tech_stack');

        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'alpha_dash', 'max:140', Rule::unique('tech_stacks', 'slug')->ignore($techStack instanceof TechStack ? $techStack->id : $techStack)],
        ];
    }
}