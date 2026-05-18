<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFamilyRequest extends FormRequest
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
            'cat_id.*'   => ['integer', 'exists:sub_category_1,id'],
            'background' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'link'       => ['nullable', 'string', 'max:255'],
        ];
    }
}
