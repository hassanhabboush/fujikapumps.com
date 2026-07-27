<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\PaginatesGrid;
use App\Http\Requests\GridPageRequest;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\Slider;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SliderController extends Controller
{
    use HandlesMediaUploads;
    use PaginatesGrid;

    private const IMAGE_DIR = 'slideruploads';

    public function index(): View
    {
        return view('Pages.slider.slider');
    }

    public function data(GridPageRequest $request): JsonResponse
    {
        return $this->paginateQuery(
            Slider::query(),
            $request,
            fn (Slider $slider): array => $this->toGridRow($slider)
        );
    }

    public function show(Slider $slider): JsonResponse
    {
        return response()->json(['data' => [$this->toGridRow($slider)]]);
    }

    public function store(StoreSliderRequest $request): RedirectResponse
    {
        Slider::create($request->safe()->except('image') + [
            'image' => $this->storeMedia($request->file('image'), self::IMAGE_DIR),
        ]);

        return redirect()->back()->with('status', 'Slide created.');
    }

    public function update(UpdateSliderRequest $request, Slider $slider): RedirectResponse
    {
        $attributes = $request->safe()->except('image');
        $oldImage = $slider->getRawOriginal('image');

        if ($file = $request->file('image')) {
            $attributes['image'] = $this->storeMedia($file, self::IMAGE_DIR);
        }

        $slider->update($attributes);

        if (isset($attributes['image'])) {
            $this->deleteMedia($oldImage, self::IMAGE_DIR);
        }

        return redirect()->back()->with('status', 'Slide updated.');
    }

    public function destroy(Slider $slider): Response
    {
        $image = $slider->getRawOriginal('image');

        $slider->delete();
        // The old delete() read the path but never removed the file.
        $this->deleteMedia($image, self::IMAGE_DIR);

        return response()->noContent();
    }

    /**
     * HasMediaUrls resolves `image` in getAttribute(), which toArray()
     * bypasses, so the row is assembled attribute-by-attribute.
     */
    private function toGridRow(Slider $slider): array
    {
        return [
            'id'         => $slider->id,
            'image'      => $slider->image,
            'text1'      => $slider->text1,
            'text2'      => $slider->text2,
            'text3'      => $slider->text3,
            'buttontext' => $slider->buttontext,
            'buttonlink' => $slider->buttonlink,
        ];
    }
}
