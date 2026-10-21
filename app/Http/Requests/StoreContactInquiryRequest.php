<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'company' => ['nullable', 'string', 'max:160'],
            'service' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ];
    }
}