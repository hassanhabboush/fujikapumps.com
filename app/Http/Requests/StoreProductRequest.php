<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StoreProductRequest extends FormRequest
{
    /** Columns the parameter CSV supplies, in column order. */
    public const CSV_COLUMNS = [
        'Model', 'SerialNumber', 'PowerKw', 'PowerHp', 'q', 'h', 'v',
        'Discharge_diameter', 'Hertz', 'Material', 'RPM', 'link',
    ];

    /**
     * Columns a row must fill to be worth importing. Every column is required
     * today — drop one from this list to start accepting rows that leave it
     * blank.
     */
    public const CSV_REQUIRED_COLUMNS = self::CSV_COLUMNS;

    /** @var array<int, array<string, string>>|null */
    private ?array $parameterRows = null;

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
            function (Validator $validator): void {
                $file = $this->file('parameter');

                if (! $file instanceof UploadedFile || $validator->errors()->has('parameter')) {
                    return;
                }

                if ($this->parameterRows() === []) {
                    $validator->errors()->add(
                        'parameter',
                        'The parameter file has no usable rows — every column of a row must be filled in.'
                    );
                }
            },
        ];
    }

    /**
     * The importable data rows of the parameter CSV, keyed by column name.
     *
     * Spreadsheet exports trail blank lines and rows of bare commas, and
     * fgetcsv() hands a blank line back as [null]; importing what it returns
     * verbatim filled product_parameter with rows carrying nothing but a
     * product_id. A row is only kept when every required column has a value.
     *
     * Parsed once and memoised, so the controller reads the same rows this
     * validator checked without opening the upload a second time.
     *
     * @return array<int, array<string, string>>
     */
    public function parameterRows(): array
    {
        if ($this->parameterRows !== null) {
            return $this->parameterRows;
        }

        $this->parameterRows = [];

        $file = $this->file('parameter');

        if (! $file instanceof UploadedFile || ! $file->isValid()) {
            return $this->parameterRows;
        }

        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return $this->parameterRows;
        }

        $isHeader = true;

        while (($cells = fgetcsv($handle, 1000, ',')) !== false) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            $row = [];

            foreach (self::CSV_COLUMNS as $i => $column) {
                $row[$column] = trim((string) ($cells[$i] ?? ''));
            }

            foreach (self::CSV_REQUIRED_COLUMNS as $column) {
                if ($row[$column] === '') {
                    continue 2;
                }
            }

            $this->parameterRows[] = $row;
        }

        fclose($handle);

        return $this->parameterRows;
    }
}
