<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'link'   => ['nullable', 'string', 'max:2048'],
            'title1' => ['nullable', 'string', 'max:255'],
            'title2' => ['nullable', 'string', 'max:255'],
            'title3' => ['nullable', 'string', 'max:255'],
            'title4' => ['nullable', 'string', 'max:255'],
            'desc1'  => ['nullable', 'string'],
            'desc2'  => ['nullable', 'string'],
            'desc3'  => ['nullable', 'string'],
            'desc4'  => ['nullable', 'string'],
            'map'    => ['nullable', 'string'],
            'image'  => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'link'  => 'YouTube link',
            'image' => 'about image',
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'The about image must be a valid image file.',
            'image.mimes' => 'The about image must be a jpg, jpeg, png or webp file.',
            'image.max'   => 'The about image may not be larger than 4 MB.',
        ];
    }
}
