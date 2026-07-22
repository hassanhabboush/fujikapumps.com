<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductParameterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Model'              => ['required', 'string', 'max:255'],
            'SerialNumber'       => ['nullable', 'string', 'max:255'],
            'PowerKw'            => ['nullable', 'string', 'max:255'],
            'PowerHp'            => ['nullable', 'string', 'max:255'],
            'q'                  => ['nullable', 'string', 'max:255'],
            'h'                  => ['nullable', 'string', 'max:255'],
            'v'                  => ['nullable', 'string', 'max:255'],
            'Discharge_diameter' => ['nullable', 'string', 'max:255'],
            'Hertz'              => ['nullable', 'string', 'max:255'],
            'Material'           => ['nullable', 'string', 'max:255'],
            'RPM'                => ['nullable', 'string', 'max:255'],
            'link'               => ['nullable', 'string', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'Model'              => 'model',
            'SerialNumber'       => 'serial number',
            'Discharge_diameter' => 'discharge diameter',
        ];
    }

    public function messages(): array
    {
        return [
            'Model.required' => 'The model is required.',
        ];
    }
}
