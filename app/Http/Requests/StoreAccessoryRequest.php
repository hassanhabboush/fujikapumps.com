<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccessoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'link'  => ['nullable', 'string', 'max:2048'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'  => 'accessory name',
            'link'  => 'link',
            'image' => 'accessory image',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'The accessory name is required.',
            'image.required' => 'An accessory image is required.',
            'image.image'    => 'The accessory image must be a valid image file.',
            'image.mimes'    => 'The accessory image must be a jpg, jpeg, png or webp file.',
            'image.max'      => 'The accessory image may not be larger than 4 MB.',
        ];
    }
}
