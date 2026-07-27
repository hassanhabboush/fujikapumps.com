<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\PaginatesGrid;
use App\Http\Requests\GridPageRequest;
use App\Http\Requests\StoreProductGalleryRequest;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class GalleryController extends Controller
{
    use HandlesMediaUploads;
    use PaginatesGrid;

    private const IMAGE_DIR = 'productimage';

    /**
     * The product now comes from the URL rather than the session, which
     * previously meant two open tabs would file uploads against whichever
     * product was opened last.
     */
    public function index(Product $product): View
    {
        return view('Pages.product.gallery.gallery')->with('id', $product->id);
    }

    public function data(GridPageRequest $request, Product $product): JsonResponse
    {
        $images = Cache::remember(
            $this->cacheKey($product),
            now()->addHour(),
            fn () => ProductGallery::where('product_id', $product->id)
                ->get()
                ->map(fn (ProductGallery $image): array => [
                    'id'   => $image->id,
                    'path' => $image->path,
                ])
                ->all()
        );

        return $this->paginateRows($images, $request);
    }

    public function store(StoreProductGalleryRequest $request, Product $product): RedirectResponse
    {
        ProductGallery::create([
            'product_id' => $product->id,
            'path'       => $this->storeMedia($request->file('background'), self::IMAGE_DIR),
        ]);

        Cache::forget($this->cacheKey($product));

        return redirect()->back()->with('status', 'Gallery image added.');
    }

    public function destroy(ProductGallery $gallery): Response
    {
        $path = $gallery->getRawOriginal('path');
        $productId = $gallery->product_id;

        $gallery->delete();
        // The old delete() left the uploaded file behind.
        $this->deleteMedia($path, self::IMAGE_DIR);

        Cache::forget('gallery_' . $productId);

        return response()->noContent();
    }

    private function cacheKey(Product $product): string
    {
        return 'gallery_' . $product->id;
    }
}
