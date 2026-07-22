<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'english_name' => ['required', 'string', 'max:255'],
            'background'   => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'english_name' => 'category name',
            'background'   => 'background image',
        ];
    }

    public function messages(): array
    {
        return [
            'english_name.required' => 'The category name is required.',
            'background.image'      => 'The background must be a valid image file.',
            'background.mimes'      => 'The background must be a jpg, jpeg, png or webp file.',
            'background.max'        => 'The background image may not be larger than 2 MB.',
        ];
    }
}
