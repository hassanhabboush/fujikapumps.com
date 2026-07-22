<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'english_name' => ['required', 'string', 'max:40'],
            'family_id'    => ['required', 'integer', 'exists:family,id'],
            'link'         => ['nullable', 'string', 'max:2048'],
            'text1'        => ['nullable', 'string', 'max:2048'],
            'text2'        => ['nullable', 'string', 'max:2048'],
            'text3'        => ['nullable', 'string', 'max:2048'],
            'image'        => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'english_name' => 'series name',
            'family_id'    => 'family',
            'image'        => 'series image',
        ];
    }

    public function messages(): array
    {
        return [
            'english_name.required' => 'The series name is required.',
            'english_name.max'      => 'The series name may not be longer than 40 characters.',
            'family_id.required'    => 'Please choose a family.',
            'family_id.exists'      => 'The selected family does not exist.',
            'image.image'           => 'The series image must be a valid image file.',
            'image.mimes'           => 'The series image must be a jpg, jpeg, png or webp file.',
            'image.max'             => 'The series image may not be larger than 4 MB.',
        ];
    }
}
