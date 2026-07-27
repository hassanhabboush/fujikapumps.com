<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Read-side request for the Kendo product grid's server paging. Kendo sends
 * `page` / `pageSize` on every read once `serverPaging` is on; both are
 * optional so a bare `/products/data` (and the existing tests) still resolve
 * to the first page.
 */
class ProductGridRequest extends FormRequest
{
    /** Matches the grid's own `pageSize` in Pages/product/product.blade.php. */
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
