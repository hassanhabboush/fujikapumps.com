<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\PaginatesGrid;
use App\Http\Requests\GridPageRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use HandlesMediaUploads;
    use PaginatesGrid;

    private const BACKGROUND_DIR = 'categorybackground';

    public function index(): View
    {
        return view('Pages.category.category');
    }

    /**
     * Grid feed. Cached as a plain array because HasMediaUrls resolves media
     * columns in getAttribute(), which toArray() bypasses — the URLs have to be
     * read attribute-by-attribute before anything is serialised.
     */
    public function data(GridPageRequest $request): JsonResponse
    {
        $categories = Cache::remember('categories', now()->addHour(), function () {
            return Category::query()
                ->get()
                ->map(fn (Category $category): array => $this->toGridRow($category))
                ->all();
        });

        return $this->paginateRows($categories, $request);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(['data' => [$this->toGridRow($category)]]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create([
            'english_name' => $request->validated('english_name'),
            'logo'         => '',
            'background'   => $this->storeMedia($request->file('background'), self::BACKGROUND_DIR),
        ]);

        return redirect()->back()->with('status', 'Category created.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $attributes = ['english_name' => $request->validated('english_name')];
        $oldBackground = $category->getRawOriginal('background');

        if ($file = $request->file('background')) {
            $attributes['background'] = $this->storeMedia($file, self::BACKGROUND_DIR);
        }

        $category->update($attributes);

        if (isset($attributes['background'])) {
            $this->deleteMedia($oldBackground, self::BACKGROUND_DIR);
        }

        return redirect()->back()->with('status', 'Category updated.');
    }

    public function destroy(Category $category): Response
    {
        $background = $category->getRawOriginal('background');

        $category->delete();
        $this->deleteMedia($background, self::BACKGROUND_DIR);

        // Called over AJAX by the grid, which has no use for a redirect.
        return response()->noContent();
    }

    /**
     * @return array{id: int, english_name: string, background: ?string}
     */
    private function toGridRow(Category $category): array
    {
        return [
            'id'           => $category->id,
            'english_name' => $category->english_name,
            'background'   => $category->background,
        ];
    }
}
