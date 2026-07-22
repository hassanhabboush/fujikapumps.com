<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Models\SubCategory;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SubCategoryController extends Controller
{
    use HandlesMediaUploads;

    private const BACKGROUND_DIR = 'categorybackground';

    public function index(): View
    {
        return view('Pages.sub_category.sub_category');
    }

    public function data(): JsonResponse
    {
        $subCategories = SubCategory::query()
            ->get()
            ->map(fn (SubCategory $subCategory): array => $this->toGridRow($subCategory))
            ->all();

        return response()->json(['data' => $subCategories]);
    }

    public function byCategory(int $id): JsonResponse
    {
        $subCategories = SubCategory::whereHas('categories', fn ($q) => $q->where('categories.id', $id))
            ->get()
            ->map(fn (SubCategory $subCategory): array => $this->toGridRow($subCategory))
            ->all();

        return response()->json(['data' => $subCategories]);
    }

    public function categoryScreen(int $id): View
    {
        return view('Pages.sub_category.subcategory')->with('cid', $id);
    }

    public function show(SubCategory $subCategory): JsonResponse
    {
        $data = $this->toGridRow($subCategory);

        // The edit modal splits this on "," to preselect the multi-select.
        $data['sub'] = $subCategory->categories()->pluck('categories.id')->implode(',');

        return response()->json(['data' => $data]);
    }

    public function store(StoreSubCategoryRequest $request): RedirectResponse
    {
        $subCategory = SubCategory::create([
            'english_name' => $request->validated('name'),
            'background'   => $this->storeMedia($request->file('background'), self::BACKGROUND_DIR),
        ]);

        $subCategory->categories()->attach($request->validated('cat_id'));
        // attach() fires no model events, so InvalidatesCache never runs here.
        Cache::forget('headerCategories');

        return redirect()->back()->with('status', 'Sub category created.');
    }

    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory): RedirectResponse
    {
        $data = ['english_name' => $request->validated('name')];
        $oldBackground = $subCategory->getRawOriginal('background');

        if ($file = $request->file('background')) {
            $data['background'] = $this->storeMedia($file, self::BACKGROUND_DIR);
        }

        $subCategory->update($data);
        $subCategory->categories()->sync($request->validated('cat_id'));
        Cache::forget('headerCategories');

        if (isset($data['background'])) {
            $this->deleteMedia($oldBackground, self::BACKGROUND_DIR);
        }

        return redirect()->back()->with('status', 'Sub category updated.');
    }

    public function destroy(SubCategory $subCategory): Response
    {
        $background = $subCategory->getRawOriginal('background');

        // The old delete() left both the pivot rows and the file behind.
        $subCategory->categories()->detach();
        $subCategory->delete();
        $this->deleteMedia($background, self::BACKGROUND_DIR);

        Cache::forget('headerCategories');

        return response()->noContent();
    }

    private function toGridRow(SubCategory $subCategory): array
    {
        return [
            'id'           => $subCategory->id,
            'english_name' => $subCategory->english_name,
            'background'   => $subCategory->background,
        ];
    }
}
