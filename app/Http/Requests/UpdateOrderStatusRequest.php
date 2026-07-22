<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'order status',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'An order status is required.',
        ];
    }
}
