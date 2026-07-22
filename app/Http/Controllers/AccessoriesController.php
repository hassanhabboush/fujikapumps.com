<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccessoryRequest;
use App\Http\Requests\UpdateAccessoryRequest;
use App\Models\Accessory;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AccessoriesController extends Controller
{
    use HandlesMediaUploads;

    private const IMAGE_DIR = 'accessoriesuploads';

    public function index(): View
    {
        return view('Pages.accessories.list');
    }

    /**
     * Cached as a plain array: HasMediaUrls resolves `photo` in getAttribute(),
     * which toArray() bypasses.
     */
    public function data(): JsonResponse
    {
        $accessories = Cache::remember('accessories', now()->addHour(), function () {
            return Accessory::query()
                ->get()
                ->map(fn (Accessory $accessory): array => $this->toGridRow($accessory))
                ->all();
        });

        return response()->json(['data' => $accessories]);
    }

    public function show(Accessory $accessory): JsonResponse
    {
        return response()->json(['data' => [$this->toGridRow($accessory)]]);
    }

    public function store(StoreAccessoryRequest $request): RedirectResponse
    {
        Accessory::create($request->safe()->except('image') + [
            'photo' => $this->storeMedia($request->file('image'), self::IMAGE_DIR),
        ]);

        return redirect()->back()->with('status', 'Accessory created.');
    }

    public function update(UpdateAccessoryRequest $request, Accessory $accessory): RedirectResponse
    {
        $attributes = $request->safe()->except('image');
        $oldPhoto = $accessory->getRawOriginal('photo');

        if ($file = $request->file('image')) {
            $attributes['photo'] = $this->storeMedia($file, self::IMAGE_DIR);
        }

        $accessory->update($attributes);

        if (isset($attributes['photo'])) {
            $this->deleteMedia($oldPhoto, self::IMAGE_DIR);
        }

        return redirect()->back()->with('status', 'Accessory updated.');
    }

    public function destroy(Accessory $accessory): Response
    {
        $photo = $accessory->getRawOriginal('photo');

        $accessory->delete();
        // The old delete() read the path but never removed the file.
        $this->deleteMedia($photo, self::IMAGE_DIR);

        return response()->noContent();
    }

    private function toGridRow(Accessory $accessory): array
    {
        return [
            'id'    => $accessory->id,
            'photo' => $accessory->photo,
            'name'  => $accessory->name,
            'link'  => $accessory->link,
        ];
    }
}
