<?php

namespace App\Services;

use App\Models\ProductParameter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Builds the pump-selection query behind the public /filter page.
 */
class ProductParameterFilter
{
    private const COLUMNS = [
        'Model', 'SerialNumber', 'q', 'h', 'PowerKw', 'PowerHp', 'v', 'Discharge_diameter', 'Hertz', 'link',
    ];

    /**
     * Placeholder values the select boxes post when nothing is chosen.
     */
    private const PLACEHOLDERS = [
        'rpm' => 'RPM',
        'material' => 'Material',
        'dm' => 'Size',
        'hertz' => 'Hertz',
        'volt' => 'Voltage',
    ];

    private const EXACT_COLUMNS = [
        'rpm' => 'RPM',
        'material' => 'Material',
        'dm' => 'Discharge_diameter',
        'hertz' => 'Hertz',
        'volt' => 'v',
    ];

    /**
     * @param  array<string, mixed>  $filters  validated input
     */
    public function apply(array $filters): Collection
    {
        $query = ProductParameter::select(self::COLUMNS);

        $this->applyTaxonomy($query, $filters);

        foreach (self::EXACT_COLUMNS as $field => $column) {
            $value = $filters[$field] ?? null;

            if ($value !== null && $value !== '' && $value !== self::PLACEHOLDERS[$field]) {
                $query->where($column, $value);
            }
        }

        foreach (['q', 'h'] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                // q and h are varchar columns, so the comparison is cast rather
                // than left to the driver — SQLite compares text lexically and
                // would drop every row.
                $query->whereRaw(
                    "CAST({$field} AS DECIMAL(10,2)) BETWEEN ? AND ?",
                    $this->tolerance((float) $filters[$field])
                );
            }
        }

        return $this->withModelPeaks($query->get());
    }

    /**
     * Category → sub-category → sub-category-1 → family, each level only
     * narrowing when the one above it was supplied.
     */
    private function applyTaxonomy(Builder $query, array $filters): void
    {
        if (empty($filters['cat_id'])) {
            return;
        }

        $query->whereHas(
            'product.family.subCategory1s.parentSubCategories.categories',
            fn ($q) => $q->where('categories.id', $filters['cat_id'])
        );

        if (empty($filters['sub_cat_id'])) {
            return;
        }

        $query->whereHas(
            'product.family.subCategory1s.parentSubCategories',
            fn ($q) => $q->where('sub_category.id', $filters['sub_cat_id'])
        );

        if (empty($filters['sub_cat_id1'])) {
            return;
        }

        $query->whereHas(
            'product.family.subCategory1s',
            fn ($q) => $q->where('sub_category_1.id', $filters['sub_cat_id1'])
        );

        if (empty($filters['family'])) {
            return;
        }

        $query->whereHas('product', fn ($q) => $q->where('family_id', $filters['family']));
    }

    /**
     * Flow and head are matched within a tolerance band that widens for small
     * values, where a fixed percentage would exclude usable pumps.
     *
     * @return array{0: float, 1: float}
     */
    private function tolerance(float $value): array
    {
        $percent = match (true) {
            $value < 20 => 50,
            $value < 35 => 30,
            $value <= 50 => 20,
            default => 10,
        };

        $delta = $value * $percent / 100;

        return [$value - $delta, $value + $delta];
    }

    /**
     * Rows are per operating point; the listing shows each model's peak duty.
     */
    private function withModelPeaks(Collection $rows): Collection
    {
        $peaks = ProductParameter::select('Model', DB::raw('MAX(q) as q'), DB::raw('MAX(h) as h'))
            ->whereIn('Model', $rows->pluck('Model')->unique()->all())
            ->groupBy('Model')
            ->get()
            ->keyBy('Model');

        return $rows->each(function ($row) use ($peaks): void {
            $row->q = $peaks[$row->Model]->q ?? null;
            $row->h = $peaks[$row->Model]->h ?? null;
        });
    }
}
