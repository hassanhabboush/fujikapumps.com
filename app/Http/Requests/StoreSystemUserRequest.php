<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSystemUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'role'     => ['required', Rule::in([1, 2, 3, 4])],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'name',
            'email'    => 'email address',
            'password' => 'password',
            'role'     => 'role',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'     => 'That email address is already registered.',
            'password.required' => 'A password is required.',
            'password.min'     => 'The password must be at least 8 characters.',
            'role.in'          => 'Please choose a valid role.',
        ];
    }
}
