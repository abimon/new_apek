<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AdminBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'superadmin';
    }

    public function rules(): array
    {
        $postId = $this->route('post')?->id;

        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'alpha_dash', 'max:200', Rule::unique('blog_posts', 'slug')->ignore($postId)],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'array'],
            'content.type' => ['required', 'in:doc'],
            'content.content' => ['required', 'array', 'min:1', 'max:500'],
            'status' => ['required', Rule::in(['draft', 'scheduled', 'published'])],
            'published_at' => $this->input('status') === 'scheduled'
                ? ['required', 'date', 'after:now']
                : ['nullable', 'date'],
            'categories' => ['nullable', 'string', 'max:1000'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'cover' => ['nullable', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('content'))) {
            $this->merge(['content' => json_decode($this->input('content'), true)]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (strlen((string) json_encode($this->input('content'))) > 200_000) {
                $validator->errors()->add('content', 'Article content must be no larger than 200 KB.');
            }
        });
    }
}
