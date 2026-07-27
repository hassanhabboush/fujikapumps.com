<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared read-side request for Kendo grids that page on the server. Kendo sends
 * `page` / `pageSize` on every read once `serverPaging` is on; both are optional
 * so a bare `.../data` call (and the existing tests) still resolve to page one.
 */
class GridPageRequest extends FormRequest
{
    /** Matches the grids' own `pageSize` in the Kendo view config. */
    private const DEFAULT_PAGE_SIZE = 8;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'     => ['nullable', 'integer', 'min:1'],
            'pageSize' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function pageNumber(): int
    {
        return (int) ($this->validated('page') ?? 1);
    }

    public function perPage(): int
    {
        return (int) ($this->validated('pageSize') ?? self::DEFAULT_PAGE_SIZE);
    }
}
