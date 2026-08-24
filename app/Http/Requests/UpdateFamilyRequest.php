<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['sometimes', 'required', 'string', 'max:255'],
            'cat_id'     => ['required', 'array', 'min:1'],
            'cat_id.*'   => ['integer', 'exists:sub_category_1,id'],
            'background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'link'       => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'family name',
            'cat_id'     => 'sub categories',
            'background' => 'background image',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'The family name is required.',
            'cat_id.required'   => 'Please choose at least one sub category.',
            'cat_id.*.exists'   => 'One of the selected sub categories does not exist.',
            'background.image'  => 'The background must be a valid image file.',
            'background.mimes'  => 'The background must be a jpg, jpeg, png or webp file.',
            'background.max'    => 'The background image may not be larger than 5 MB.',
        ];
    }
}
