<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Facebook'  => ['nullable', 'string', 'max:255'],
            'Twitter'   => ['nullable', 'string', 'max:255'],
            'Linkedin'  => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'whatsapp'  => ['nullable', 'string', 'max:255'],
            'email'     => ['nullable', 'email', 'max:255'],
            'phone1'    => ['nullable', 'string', 'max:255'],
            'phon2'     => ['nullable', 'string', 'max:255'],
            'address'   => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'Facebook'  => 'Facebook link',
            'Twitter'   => 'Twitter link',
            'Linkedin'  => 'LinkedIn link',
            'instagram' => 'Instagram link',
            'whatsapp'  => 'WhatsApp number',
            'email'     => 'email address',
            'phone1'    => 'first phone number',
            'phon2'     => 'second phone number',
            'address'   => 'address',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'The email address must be a valid email.',
        ];
    }
}
