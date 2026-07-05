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
            'Eid'           => ['required', 'integer', 'exists:categories,id'],
            'Eenglish_name' => ['required', 'string', 'max:255'],
            'Ebackground'   => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'Eid'           => 'category',
            'Eenglish_name' => 'category name',
            'Ebackground'   => 'background image',
        ];
    }

    public function messages(): array
    {
        return [
            'Eid.required'           => 'The category is required.',
            'Eid.exists'             => 'The selected category does not exist.',
            'Eenglish_name.required' => 'The category name is required.',
            'Ebackground.image'      => 'The background must be a valid image file.',
            'Ebackground.mimes'      => 'The background must be a jpg, jpeg, png or webp file.',
            'Ebackground.max'        => 'The background image may not be larger than 2 MB.',
        ];
    }
}
