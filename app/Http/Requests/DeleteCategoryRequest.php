<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'category',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The category is required.',
            'id.exists'   => 'The selected category does not exist.',
        ];
    }
}
