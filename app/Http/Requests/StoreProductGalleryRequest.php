<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'background' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'background' => 'gallery image',
        ];
    }

    public function messages(): array
    {
        return [
            'background.required' => 'A gallery image is required.',
            'background.image'    => 'The gallery image must be a valid image file.',
            'background.mimes'    => 'The gallery image must be a jpg, jpeg, png or webp file.',
            'background.max'      => 'The gallery image may not be larger than 4 MB.',
        ];
    }
}
