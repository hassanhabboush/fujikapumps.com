<?php

namespace App\Http\Controllers\Concerns;

use App\Http\Requests\GridPageRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

/**
 * Server-side paging for the admin Kendo grids. Two entry points, depending on
 * whether the feed is cached:
 *
 *   - paginateQuery(): uncached feeds page at the database (LIMIT/OFFSET plus a
 *     separate count), so the whole table is never materialised.
 *   - paginateRows(): an already-materialised (e.g. cached) row array is sliced
 *     in PHP, leaving the cache key and its invalidation contract untouched.
 *
 * Both answer Kendo with { data: [...page...], total: <full count> }.
 */
trait PaginatesGrid
{
    private function paginateQuery(Builder $query, GridPageRequest $request, callable $map): JsonResponse
    {
        $total = (clone $query)->count();

        $rows = $query->forPage($request->pageNumber(), $request->perPage())
            ->get()
            ->map($map)
            ->all();

        return response()->json(['data' => $rows, 'total' => $total]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function paginateRows(array $rows, GridPageRequest $request): JsonResponse
    {
        $offset = ($request->pageNumber() - 1) * $request->perPage();

        return response()->json([
            'data'  => array_values(array_slice($rows, $offset, $request->perPage())),
            'total' => count($rows),
        ]);
    }
}
