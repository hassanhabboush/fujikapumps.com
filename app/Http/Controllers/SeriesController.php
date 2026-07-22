<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeriesRequest;
use App\Http\Requests\UpdateSeriesRequest;
use App\Models\Series;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SeriesController extends Controller
{
    use HandlesMediaUploads;

    private const IMAGE_DIR = 'seriesuploads';

    public function index(): View
    {
        return view('Pages.series.list');
    }

    public function data(): JsonResponse
    {
        $series = Series::query()
            ->with('family')
            ->get()
            ->map(fn (Series $item): array => $this->toGridRow($item))
            ->all();

        return response()->json(['data' => $series]);
    }

    public function show(Series $series): JsonResponse
    {
        return response()->json(['data' => [$this->toGridRow($series)]]);
    }

    public function store(StoreSeriesRequest $request): RedirectResponse
    {
        Series::create($request->safe()->except('image') + [
            'photo' => $this->storeMedia($request->file('image'), self::IMAGE_DIR),
        ]);

        return redirect()->back()->with('status', 'Series created.');
    }

    public function update(UpdateSeriesRequest $request, Series $series): RedirectResponse
    {
        $attributes = $request->safe()->except('image');
        $oldPhoto = $series->getRawOriginal('photo');

        if ($file = $request->file('image')) {
            $attributes['photo'] = $this->storeMedia($file, self::IMAGE_DIR);
        }

        $series->update($attributes);

        if (isset($attributes['photo'])) {
            $this->deleteMedia($oldPhoto, self::IMAGE_DIR);
        }

        return redirect()->back()->with('status', 'Series updated.');
    }

    public function destroy(Series $series): Response
    {
        $photo = $series->getRawOriginal('photo');

        $series->delete();
        // The old delete() read the path but never removed the file.
        $this->deleteMedia($photo, self::IMAGE_DIR);

        return response()->noContent();
    }

    private function toGridRow(Series $series): array
    {
        return [
            'id'           => $series->id,
            'photo'        => $series->photo,
            'english_name' => $series->english_name,
            'link'         => $series->link,
            'text1'        => $series->text1,
            'text2'        => $series->text2,
            'text3'        => $series->text3,
            'family_id'    => $series->family_id,
        ];
    }
}
