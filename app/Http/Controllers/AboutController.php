<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAboutImageRequest;
use App\Http\Requests\UpdateAboutRequest;
use App\Models\About;
use App\Models\Gallery;
use App\Models\Team;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * The about page is a single row plus two flat image collections (gallery and
 * team), so this controller exposes three small groups rather than one CRUD.
 */
class AboutController extends Controller
{
    use HandlesMediaUploads;

    private const ABOUT_DIR   = 'accessoriesuploads';
    private const GALLERY_DIR = 'gallery';
    private const TEAM_DIR    = 'team';

    // ---- about details -------------------------------------------------

    public function index(): View
    {
        return view('Pages.about.about');
    }

    public function show(): JsonResponse
    {
        return response()->json(['data' => About::firstOrFail()]);
    }

    public function update(UpdateAboutRequest $request): RedirectResponse
    {
        $about = About::firstOrFail();

        $fields = [
            'linkyoutube' => $request->validated('link'),
            'title1'      => $request->validated('title1'),
            'title2'      => $request->validated('title2'),
            'title3'      => $request->validated('title3'),
            'title4'      => $request->validated('title4'),
            'desc1'       => $request->validated('desc1'),
            'desc2'       => $request->validated('desc2'),
            'desc3'       => $request->validated('desc3'),
            'desc4'       => $request->validated('desc4'),
            'map'         => $request->validated('map'),
        ];

        $oldPhoto = $about->getRawOriginal('photo');

        if ($file = $request->file('image')) {
            $fields['photo'] = $this->storeMedia($file, self::ABOUT_DIR);
        }

        $about->update($fields);

        if (isset($fields['photo'])) {
            $this->deleteMedia($oldPhoto, self::ABOUT_DIR);
        }

        return redirect()->back()->with('status', 'About page updated.');
    }

    // ---- gallery -------------------------------------------------------

    public function gallery(): View
    {
        return view('Pages.about.gallery.gallery');
    }

    public function galleryData(): JsonResponse
    {
        return response()->json([
            'data' => Gallery::query()
                ->get()
                ->map(fn (Gallery $image): array => ['id' => $image->id, 'path' => $image->path])
                ->all(),
        ]);
    }

    public function storeGalleryImage(StoreAboutImageRequest $request): RedirectResponse
    {
        Gallery::create([
            'path' => $this->storeMedia($request->file('background'), self::GALLERY_DIR),
        ]);

        return redirect()->back()->with('status', 'Gallery image added.');
    }

    public function destroyGalleryImage(Gallery $gallery): Response
    {
        $path = $gallery->getRawOriginal('path');

        $gallery->delete();
        // The old deletegallery() left the uploaded file behind.
        $this->deleteMedia($path, self::GALLERY_DIR);

        return response()->noContent();
    }

    // ---- team ----------------------------------------------------------

    public function team(): View
    {
        return view('Pages.about.team.team');
    }

    public function teamData(): JsonResponse
    {
        return response()->json([
            'data' => Team::query()
                ->get()
                ->map(fn (Team $member): array => ['id' => $member->id, 'path' => $member->path])
                ->all(),
        ]);
    }

    public function storeTeamImage(StoreAboutImageRequest $request): RedirectResponse
    {
        Team::create([
            'path' => $this->storeMedia($request->file('background'), self::TEAM_DIR),
        ]);

        return redirect()->back()->with('status', 'Team image added.');
    }

    public function destroyTeamImage(Team $team): Response
    {
        $path = $team->getRawOriginal('path');

        $team->delete();
        $this->deleteMedia($path, self::TEAM_DIR);

        return response()->noContent();
    }
}
