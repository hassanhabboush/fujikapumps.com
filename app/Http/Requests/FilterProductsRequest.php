<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cat_id'      => ['nullable', 'integer'],
            'sub_cat_id'  => ['nullable', 'integer'],
            'sub_cat_id1' => ['nullable', 'integer'],
            'family'      => ['nullable', 'integer'],
            'rpm'         => ['nullable', 'string', 'max:100'],
            'material'    => ['nullable', 'string', 'max:100'],
            'dm'          => ['nullable', 'string', 'max:100'],
            'hertz'       => ['nullable', 'string', 'max:100'],
            'volt'        => ['nullable', 'string', 'max:100'],
            'q'           => ['nullable', 'numeric', 'min:0'],
            'h'           => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
