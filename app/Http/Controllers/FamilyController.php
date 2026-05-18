<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFamilyRequest;
use App\Http\Requests\UpdateFamilyRequest;
use App\Models\Family;
use App\Models\FamilySubcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FamilyController extends Controller
{
    public function index(): View
    {
        return view('Pages.family.family');
    }

    public function list(): JsonResponse
    {
        $families = Family::query()->get()->map(function ($family) {
            // Access the background attribute which will trigger the trait's getAttribute method
            $family->background = $family->background;
            return $family;
        });

        return response()->json(['data' => $families]);
    }

    public function listByCategory(int $cid): JsonResponse
    {
        $families = Family::whereHas('subCategory1s', function ($q) use ($cid) {
            $q->where('sub_category_1.id', $cid);
        })->select('id', 'link', 'english_name', 'background')->get();

        return response()->json(['data' => $families]);
    }

    public function categoryfamily(int $cid): View
    {
        return view('Pages.family.subcategory')->with('cid', $cid);
    }

    public function store(StoreFamilyRequest $request): RedirectResponse
    {
        $path = $request->file('background')->store('categorybackground', 'public');

        $family = Family::create([
            'english_name' => $request->input('name'),
            'background'   => $path,
            'link'         => $request->input('link'),
        ]);

        foreach ($request->input('cat_id') as $category) {
            FamilySubcategory::create([
                'family_id'       => $family->id,
                'sub_category_id' => $category,
            ]);
        }

        return redirect()->back();
    }

    public function show(Family $family): JsonResponse
    {
        $sub = FamilySubcategory::where('family_id', $family->id)
            ->pluck('sub_category_id')
            ->implode(',');

        $data        = $family->only(['id', 'english_name', 'background', 'link']);
        $data['sub'] = $sub;

        return response()->json(['data' => $data]);
    }

    public function update(UpdateFamilyRequest $request, Family $family): RedirectResponse
    {
        $data = [
            'english_name' => $request->input('name'),
            'link'         => $request->input('link'),
        ];

        if ($request->hasFile('background')) {
            $data['background'] = $request->file('background')->store('categorybackground', 'public');
        }

        $family->update($data);

        FamilySubcategory::where('family_id', $family->id)->delete();

        foreach ($request->input('cat_id') as $category) {
            FamilySubcategory::create([
                'family_id'       => $family->id,
                'sub_category_id' => $category,
            ]);
        }

        return redirect()->back();
    }

    public function destroy(Family $family): RedirectResponse
    {
        $family->delete();
        FamilySubcategory::where('family_id', $family->id)->delete();

        return redirect()->back();
    }
}
