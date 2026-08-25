<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFamilyBackgroundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'background' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'background' => 'background image',
        ];
    }

    public function messages(): array
    {
        return [
            'background.required' => 'Please choose a background image.',
            'background.image'    => 'The background must be a valid image file.',
            'background.mimes'    => 'The background must be a jpg, jpeg, png or webp file.',
            'background.max'      => 'The background image may not be larger than 5 MB.',
        ];
    }
}
