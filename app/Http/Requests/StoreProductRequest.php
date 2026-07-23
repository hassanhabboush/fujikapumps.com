<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'shortdescreption'  => ['nullable', 'string'],
            'link'              => ['nullable', 'string', 'max:2048'],
            'cat_id'            => ['required', 'integer', 'exists:family,id'],
            'background'        => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'images'            => ['nullable', 'array'],
            'images.*'          => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            // xlsx is not accepted: there is no spreadsheet library, and the
            // importer reads the upload with fgetcsv(), so a binary workbook
            // lands in product_parameter as garbage bytes and MySQL rejects it.
            'parameter'         => ['nullable', 'file', 'mimes:csv,txt', 'extensions:csv,txt', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'product name',
            'shortdescreption' => 'description',
            'cat_id'           => 'family',
            'background'       => 'product image',
            'images'           => 'gallery images',
            'parameter'        => 'parameter file',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'The product name is required.',
            'cat_id.required'     => 'Please choose a family.',
            'cat_id.exists'       => 'The selected family does not exist.',
            'background.required' => 'A product image is required.',
            'background.image'    => 'The product image must be a valid image file.',
            'parameter.mimes'      => 'The parameter file must be a csv file.',
            'parameter.extensions' => 'The parameter file must be a csv file.',
            'parameter.max'        => 'The parameter file may not be larger than 2 MB.',
        ];
    }

    /**
     * A file can pass the mime/extension checks and still not be readable text
     * (a renamed workbook, a UTF-16 export). Reject it here rather than letting
     * fgetcsv() feed binary into product_parameter.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $file = $this->file('parameter');

                if (! $file instanceof UploadedFile || $validator->errors()->has('parameter')) {
                    return;
                }

                $head = (string) file_get_contents($file->getRealPath(), false, null, 0, 8192);

                if ($head !== '' && ! mb_check_encoding($head, 'UTF-8')) {
                    $validator->errors()->add(
                        'parameter',
                        'The parameter file must be a plain UTF-8 csv file.'
                    );
                }
            },
        ];
    }
}
