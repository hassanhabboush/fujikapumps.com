<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Uploads still land in public/categorybackground/ rather than the public
     * disk, so legacy rows and the .webp siblings HasMediaUrls looks for keep
     * resolving from a single directory.
     */
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
    public function data(): JsonResponse
    {
        $categories = Cache::remember('categories', now()->addHour(), function () {
            return Category::query()
                ->get()
                ->map(fn (Category $category): array => $this->toGridRow($category))
                ->all();
        });

        return response()->json(['data' => $categories]);
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
            'background'   => $this->storeBackground($request->file('background')),
        ]);

        return redirect()->back();
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $attributes = ['english_name' => $request->validated('english_name')];
        $oldBackground = $category->getRawOriginal('background');

        if ($file = $request->file('background')) {
            $attributes['background'] = $this->storeBackground($file);
        }

        $category->update($attributes);

        if (isset($attributes['background'])) {
            $this->deleteBackground($oldBackground);
        }

        return redirect()->back();
    }

    public function destroy(Category $category): Response
    {
        $background = $category->getRawOriginal('background');

        $category->delete();
        $this->deleteBackground($background);

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

    /**
     * Move an upload into the background directory and return the stored path.
     */
    private function storeBackground(UploadedFile $file): string
    {
        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path(self::BACKGROUND_DIR), $name);

        return 'public/' . self::BACKGROUND_DIR . '/' . $name;
    }

    /**
     * Remove a background file given its stored `public/categorybackground/<file>`
     * path, along with the .webp sibling HasMediaUrls prefers — leaving the
     * sibling behind would orphan the image that was actually being served.
     */
    private function deleteBackground(?string $storedPath): void
    {
        if (blank($storedPath)) {
            return;
        }

        $fullPath = public_path(self::BACKGROUND_DIR . '/' . basename($storedPath));
        $webpPath = preg_replace('/\.[^.]+$/', '.webp', $fullPath);

        foreach (array_unique([$fullPath, $webpPath]) as $path) {
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }
}
