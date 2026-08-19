<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StoreProductRequest extends FormRequest
{
    /** Columns written to product_parameter, in column order. */
    public const CSV_COLUMNS = [
        'Model', 'SerialNumber', 'PowerKw', 'PowerHp', 'q', 'h', 'v',
        'Discharge_diameter', 'Hertz', 'Material', 'RPM', 'link',
    ];

    /**
     * A data row is imported when Model is filled. Other cells may be blank.
     * Empty / header-only files do not block creating the product.
     */
    public const CSV_REQUIRED_COLUMNS = ['Model'];

    /** @var array<string, string> */
    private const HEADER_ALIASES = [
        'model' => 'Model',
        'serialnumber' => 'SerialNumber',
        'powerkw' => 'PowerKw',
        'powerhp' => 'PowerHp',
        'q' => 'q',
        'qm3h' => 'q',
        'h' => 'h',
        'head' => 'h',
        'headm' => 'h',
        'v' => 'v',
        'discharge' => 'Discharge_diameter',
        'dischargediameter' => 'Discharge_diameter',
        'dischargediameteroutlet' => 'Discharge_diameter',
        'hertz' => 'Hertz',
        'material' => 'Material',
        'rpm' => 'RPM',
        'link' => 'link',
    ];

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
            'parameter'         => ['nullable', 'file', 'extensions:csv,txt', 'max:2048'],
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
            'parameter.extensions' => 'The parameter file must be a .csv file. Excel workbooks (.xlsx / .xls) are not accepted — in Excel use File → Save As → CSV (Comma delimited).',
            'parameter.max'        => 'The parameter file may not be larger than 2 MB.',
        ];
    }

    /**
     * Reject binary workbooks. Encoding mismatches are converted, not failed,
     * so Excel's Windows CSV still imports. A missing or empty CSV is fine.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! $this->hasFile('parameter') || $validator->errors()->has('parameter')) {
                    return;
                }

                $file = $this->file('parameter');

                if (! $file instanceof UploadedFile || ! $file->isValid()) {
                    return;
                }

                $head = (string) file_get_contents($file->getRealPath(), false, null, 0, 8);

                if (str_starts_with($head, "PK\x03\x04")) {
                    $validator->errors()->add(
                        'parameter',
                        'The parameter file must be a .csv file. Excel workbooks (.xlsx / .xls) are not accepted — in Excel use File → Save As → CSV (Comma delimited).'
                    );
                }
            },
        ];
    }

    /**
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

        $raw = (string) file_get_contents($file->getRealPath());

        if ($raw === '') {
            return $this->parameterRows;
        }

        $raw = $this->toUtf8Csv($raw);

        if ($raw === '' || str_starts_with($raw, "PK\x03\x04")) {
            return $this->parameterRows;
        }

        $delimiter = substr_count($raw, ';') > substr_count($raw, ',') ? ';' : ',';
        $handle = fopen('php://temp', 'r+');

        if ($handle === false) {
            return $this->parameterRows;
        }

        fwrite($handle, $raw);
        rewind($handle);

        $first = fgetcsv($handle, 0, $delimiter);
        $headerMap = is_array($first) ? $this->headerMap($first) : [];

        if ($headerMap === []) {
            $this->ingestCells(is_array($first) ? $first : []);
        }

        while (($cells = fgetcsv($handle, 0, $delimiter)) !== false) {
            $this->ingestCells($cells, $headerMap);
        }

        fclose($handle);

        return $this->parameterRows;
    }

    /**
     * @param  array<int, string|null>  $cells
     * @param  array<int, string>  $headerMap  index => column name
     */
    private function ingestCells(array $cells, array $headerMap = []): void
    {
        $row = array_fill_keys(self::CSV_COLUMNS, '');

        if ($headerMap === []) {
            foreach (self::CSV_COLUMNS as $i => $column) {
                $row[$column] = trim((string) ($cells[$i] ?? ''));
            }
        } else {
            foreach ($headerMap as $i => $column) {
                $row[$column] = trim((string) ($cells[$i] ?? ''));
            }
        }

        foreach (self::CSV_REQUIRED_COLUMNS as $column) {
            if ($row[$column] === '') {
                return;
            }
        }

        $this->parameterRows[] = $row;
    }

    /**
     * @param  array<int, string|null>  $cells
     * @return array<int, string>
     */
    private function headerMap(array $cells): array
    {
        $map = [];

        foreach ($cells as $i => $cell) {
            $alias = self::HEADER_ALIASES[$this->normalizeHeader((string) $cell)] ?? null;

            if ($alias !== null) {
                $map[$i] = $alias;
            }
        }

        return count($map) >= 3 ? $map : [];
    }

    private function normalizeHeader(string $header): string
    {
        $header = strtolower(trim(preg_replace('/\s+/u', '', $header) ?? $header));

        return (string) preg_replace('/[^a-z0-9]/', '', $header);
    }

    private function toUtf8Csv(string $raw): string
    {
        if (str_starts_with($raw, "\xFF\xFE") || str_starts_with($raw, "\xFE\xFF")) {
            $raw = mb_convert_encoding($raw, 'UTF-8', 'UTF-16');
        } elseif (! mb_check_encoding($raw, 'UTF-8')) {
            $raw = mb_convert_encoding($raw, 'UTF-8', 'Windows-1252');
        }

        if (str_starts_with($raw, "\xEF\xBB\xBF")) {
            $raw = substr($raw, 3);
        }

        return $raw;
    }
}
