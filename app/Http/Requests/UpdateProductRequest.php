<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'shortdescreption' => ['nullable', 'string'],
            'link'             => ['nullable', 'string', 'max:2048'],
            'cat_id'           => ['required', 'integer', 'exists:family,id'],
            'background'       => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'product name',
            'shortdescreption' => 'description',
            'cat_id'           => 'family',
            'background'       => 'product image',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'The product name is required.',
            'cat_id.required'  => 'Please choose a family.',
            'cat_id.exists'    => 'The selected family does not exist.',
            'background.image' => 'The product image must be a valid image file.',
        ];
    }
}
