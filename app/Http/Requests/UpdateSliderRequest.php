<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text1'      => ['nullable', 'string', 'max:255'],
            'text2'      => ['nullable', 'string', 'max:255'],
            'text3'      => ['nullable', 'string', 'max:255'],
            'buttontext' => ['nullable', 'string', 'max:255'],
            'buttonlink' => ['nullable', 'string', 'max:255'],
            'image'      => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'text1'      => 'first line',
            'text2'      => 'second line',
            'text3'      => 'third line',
            'buttontext' => 'button text',
            'buttonlink' => 'button link',
            'image'      => 'slide image',
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'The slide image must be a valid image file.',
            'image.mimes' => 'The slide image must be a jpg, jpeg, png or webp file.',
            'image.max'   => 'The slide image may not be larger than 4 MB.',
        ];
    }
}
