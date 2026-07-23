<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFamilyRequest;
use App\Http\Requests\UpdateFamilyRequest;
use App\Models\Family;
use App\Models\FamilySubcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FamilyController extends Controller
{
    public function index(): View
    {
        return view('Pages.family.family');
    }

    /**
     * Cached as a plain array because HasMediaUrls resolves `background` in
     * getAttribute(), which toArray() bypasses.
     */
    public function data(): JsonResponse
    {
        $families = Cache::remember('families', now()->addHour(), function () {
            return Family::query()
                ->get()
                ->map(fn (Family $family): array => $this->toGridRow($family))
                ->all();
        });

        return response()->json(['data' => $families]);
    }

    public function listByCategory(int $cid): JsonResponse
    {
        $families = Family::whereHas('subCategory1s', fn ($q) => $q->where('sub_category_1.id', $cid))
            ->get()
            ->map(fn (Family $family): array => $this->toGridRow($family))
            ->all();

        return response()->json(['data' => $families]);
    }

    public function categoryfamily(int $cid): View
    {
        return view('Pages.family.subcategory')->with('cid', $cid);
    }

    public function show(Family $family): JsonResponse
    {
        $data = $this->toGridRow($family);
        $data['sub'] = FamilySubcategory::where('family_id', $family->id)
            ->pluck('sub_category_id')
            ->implode(',');

        return response()->json(['data' => $data]);
    }

    public function store(StoreFamilyRequest $request): RedirectResponse
    {
        $family = Family::create([
            'english_name' => $request->validated('name'),
            'background'   => $request->file('background')->store('categorybackground', 'public'),
            'link'         => $request->validated('link'),
        ]);

        $this->syncSubCategories($family, $request->validated('cat_id'));

        return redirect()->back()->with('status', 'Family created.');
    }

    public function update(UpdateFamilyRequest $request, Family $family): RedirectResponse
    {
        $data = [
            'english_name' => $request->validated('name'),
            'link'         => $request->validated('link'),
        ];

        $oldBackground = $family->getRawOriginal('background');

        if ($request->hasFile('background')) {
            $data['background'] = $request->file('background')->store('categorybackground', 'public');
        }

        $family->update($data);

        $this->syncSubCategories($family, $request->validated('cat_id'));

        if (isset($data['background']) && filled($oldBackground)) {
            Storage::disk('public')->delete($oldBackground);
        }

        return redirect()->back()->with('status', 'Family updated.');
    }

    /**
     * Answered with 204 rather than a redirect: the grid deletes over AJAX and
     * would follow a 302 with DELETE onto the index URL, which has no such route.
     */
    public function destroy(Family $family): Response
    {
        $background = $family->getRawOriginal('background');

        FamilySubcategory::where('family_id', $family->id)->delete();
        $family->delete();

        // The old destroy() left the uploaded background behind.
        if (filled($background)) {
            Storage::disk('public')->delete($background);
        }

        $this->forgetFamilyCaches();

        return response()->noContent();
    }

    /**
     * Rewrite a family's sub-category links.
     *
     * These are plain rows rather than a relation sync, and pivot writes fire
     * no model events, so the caches that embed the category tree have to be
     * forgotten by hand.
     *
     * @param  array<int, int|string>  $subCategoryIds
     */
    private function syncSubCategories(Family $family, array $subCategoryIds): void
    {
        FamilySubcategory::where('family_id', $family->id)->delete();

        foreach ($subCategoryIds as $subCategoryId) {
            FamilySubcategory::create([
                'family_id'       => $family->id,
                'sub_category_id' => $subCategoryId,
            ]);
        }

        $this->forgetFamilyCaches();
    }

    private function forgetFamilyCaches(): void
    {
        Cache::forget('families');
        Cache::forget('headerCategories');
    }

    private function toGridRow(Family $family): array
    {
        return [
            'id'           => $family->id,
            'english_name' => $family->english_name,
            'background'   => $family->background,
            'link'         => $family->link,
        ];
    }
}
