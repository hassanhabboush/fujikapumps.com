<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared by the about-page gallery and the team grid — both take a single
 * image and nothing else.
 */
class StoreAboutImageRequest extends FormRequest
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
            'background' => 'image',
        ];
    }

    public function messages(): array
    {
        return [
            'background.required' => 'An image is required.',
            'background.image'    => 'The file must be a valid image.',
            'background.mimes'    => 'The image must be a jpg, jpeg, png or webp file.',
            'background.max'      => 'The image may not be larger than 4 MB.',
        ];
    }
}
