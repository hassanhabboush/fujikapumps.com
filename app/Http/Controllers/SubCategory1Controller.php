<?php

namespace App\Http\Controllers;

use App\Http\Requests\GridPageRequest;
use App\Http\Requests\StoreSubCategory1Request;
use App\Http\Requests\UpdateSubCategory1Request;
use App\Models\SubCategory1;
use App\Traits\HandlesMediaUploads;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SubCategory1Controller extends Controller
{
    use HandlesMediaUploads;

    private const BACKGROUND_DIR = 'categorybackground';

    public function index(): View
    {
        return view('Pages.sub_category1.sub_category');
    }

    public function data(GridPageRequest $request): JsonResponse
    {
        return $this->pagedRows(SubCategory1::query(), $request);
    }

    public function byParent(GridPageRequest $request, int $id): JsonResponse
    {
        return $this->pagedRows(
            SubCategory1::whereHas(
                'parentSubCategories',
                fn ($q) => $q->where('sub_category.id', $id)
            ),
            $request
        );
    }

    public function parentScreen(int $id): View
    {
        return view('Pages.sub_category1.subcategory')->with('cid', $id);
    }

    /**
     * Used by the add form to warn about a duplicate name before submitting.
     */
    public function countByName(string $name): JsonResponse
    {
        return response()->json([
            'data' => SubCategory1::where('english_name', $name)->count(),
        ]);
    }

    public function show(SubCategory1 $subCategory1): JsonResponse
    {
        $data = $this->toGridRow($subCategory1);

        // The edit modal splits this on "," to preselect the multi-select.
        $data['sub'] = $subCategory1->parentSubCategories()
            ->pluck('sub_category.id')
            ->implode(',');

        return response()->json(['data' => $data]);
    }

    public function store(StoreSubCategory1Request $request): RedirectResponse
    {
        $subCategory1 = SubCategory1::create([
            'english_name' => $request->validated('name'),
            'background'   => $this->storeMedia($request->file('background'), self::BACKGROUND_DIR),
        ]);

        $subCategory1->parentSubCategories()->attach($request->validated('cat_id'));
        // attach() fires no model events, so InvalidatesCache never runs here.
        Cache::forget('headerCategories');

        return redirect()->back()->with('status', 'Sub category created.');
    }

    public function update(UpdateSubCategory1Request $request, SubCategory1 $subCategory1): RedirectResponse
    {
        $data = ['english_name' => $request->validated('name')];
        $oldBackground = $subCategory1->getRawOriginal('background');

        if ($file = $request->file('background')) {
            $data['background'] = $this->storeMedia($file, self::BACKGROUND_DIR);
        }

        $subCategory1->update($data);
        $subCategory1->parentSubCategories()->sync($request->validated('cat_id'));
        Cache::forget('headerCategories');

        if (isset($data['background'])) {
            $this->deleteMedia($oldBackground, self::BACKGROUND_DIR);
        }

        return redirect()->back()->with('status', 'Sub category updated.');
    }

    public function destroy(SubCategory1 $subCategory1): Response
    {
        $background = $subCategory1->getRawOriginal('background');

        // The old delete() left both the pivot rows and the file behind.
        $subCategory1->parentSubCategories()->detach();
        $subCategory1->delete();
        $this->deleteMedia($background, self::BACKGROUND_DIR);

        Cache::forget('headerCategories');

        return response()->noContent();
    }

    /**
     * One server-side page of a grid feed. These feeds are uncached, so the
     * page is fetched at the database with LIMIT/OFFSET and the pager gets the
     * unfiltered total from a separate count rather than the whole table.
     */
    private function pagedRows(Builder $query, GridPageRequest $request): JsonResponse
    {
        $page     = $request->pageNumber();
        $pageSize = $request->perPage();

        $total = (clone $query)->count();

        $rows = $query->skip(($page - 1) * $pageSize)
            ->take($pageSize)
            ->get()
            ->map(fn (SubCategory1 $subCategory): array => $this->toGridRow($subCategory))
            ->all();

        return response()->json(['data' => $rows, 'total' => $total]);
    }

    private function toGridRow(SubCategory1 $subCategory1): array
    {
        return [
            'id'           => $subCategory1->id,
            'english_name' => $subCategory1->english_name,
            'background'   => $subCategory1->background,
        ];
    }
}
