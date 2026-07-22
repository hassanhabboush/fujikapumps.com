<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'shortdescreption'  => ['nullable', 'string'],
            'link'              => ['nullable', 'string', 'max:2048'],
            'cat_id'            => ['required', 'integer', 'exists:family,id'],
            'background'        => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'images'            => ['nullable', 'array'],
            'images.*'          => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'parameter'         => ['nullable', 'file', 'mimes:csv,txt,xlsx', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'product name',
            'shortdescreption' => 'description',
            'cat_id'           => 'family',
            'background'       => 'product image',
            'images'           => 'gallery images',
            'parameter'        => 'parameter file',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'The product name is required.',
            'cat_id.required'     => 'Please choose a family.',
            'cat_id.exists'       => 'The selected family does not exist.',
            'background.required' => 'A product image is required.',
            'background.image'    => 'The product image must be a valid image file.',
            'parameter.mimes'     => 'The parameter file must be a csv or xlsx file.',
            'parameter.max'       => 'The parameter file may not be larger than 2 MB.',
        ];
    }
}
