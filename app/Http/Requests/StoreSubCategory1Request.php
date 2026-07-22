<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubCategory1Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'cat_id'     => ['required', 'array', 'min:1'],
            'cat_id.*'   => ['integer', 'exists:sub_category,id'],
            'background' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'sub category name',
            'cat_id'     => 'parent sub categories',
            'background' => 'background image',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'The sub category name is required.',
            'cat_id.required'     => 'Please choose at least one parent sub category.',
            'cat_id.*.exists'     => 'One of the selected parent sub categories does not exist.',
            'background.required' => 'A background image is required.',
            'background.image'    => 'The background must be a valid image file.',
            'background.mimes'    => 'The background must be a jpg, jpeg, png or webp file.',
            'background.max'      => 'The background image may not be larger than 2 MB.',
        ];
    }
}
