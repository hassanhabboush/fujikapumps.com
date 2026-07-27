<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductGridRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductParameter;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    use HandlesMediaUploads;

    private const PHOTO_DIR   = 'productbackground';
    private const GALLERY_DIR = 'productimage';
    private const CSV_DIR     = 'productcsv';

    // ---- screens -------------------------------------------------------

    public function index(): View
    {
        return view('Pages.product.product');
    }

    public function create(): View
    {
        return view('Pages.product.addproduct');
    }

    public function editForm(Product $product): View
    {
        return view('Pages.product.editproduct')->with('product', $product);
    }

    public function details(Product $product): View
    {
        return view('Pages.product.productdetails', ['productDetails' => $product]);
    }

    public function featuredScreen(): View
    {
        return view('Pages.product.featureproduct');
    }

    public function categoryScreen(int $id): View
    {
        return view('Pages.product.categoryproduct')->with('id', $id);
    }

    public function subCategoryScreen(int $id): View
    {
        return view('Pages.product.subcategoryproduct')->with('id', $id);
    }

    // ---- feeds ---------------------------------------------------------

    public function data(ProductGridRequest $request): JsonResponse
    {
        return $this->pagedRows(
            'products_all',
            fn () => Product::query(),
            $request->pageNumber(),
            $request->perPage()
        );
    }

    public function featuredData(ProductGridRequest $request): JsonResponse
    {
        return $this->pagedRows(
            'products_featured',
            fn () => Product::where('is_featured', 1),
            $request->pageNumber(),
            $request->perPage()
        );
    }

    public function byCategory(ProductGridRequest $request, int $id): JsonResponse
    {
        return $this->pagedRows(
            'products_category_' . $id,
            fn () => Product::whereHas('categories', fn ($q) => $q->where('categories.id', $id)),
            $request->pageNumber(),
            $request->perPage()
        );
    }

    public function bySubCategory(ProductGridRequest $request, int $id): JsonResponse
    {
        return $this->pagedRows(
            'products_subcategory_' . $id,
            fn () => Product::whereHas('subCategories', fn ($q) => $q->where('sub_category.id', $id)),
            $request->pageNumber(),
            $request->perPage()
        );
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json(['data' => [$this->toGridRow($product)]]);
    }

    // ---- writes --------------------------------------------------------

    public function store(StoreProductRequest $request): RedirectResponse
    {
        // One transaction so a rejected parameter row cannot leave an orphan
        // product with no parameters behind.
        DB::transaction(function () use ($request): void {
            $product = Product::create([
                'name'        => $request->validated('name'),
                'descreption' => $request->validated('shortdescreption'),
                'photo'       => $this->storeMedia($request->file('background'), self::PHOTO_DIR),
                'is_featured' => 0,
                'family_id'   => $request->validated('cat_id'),
                'link'        => $request->validated('link') ?? '',
            ]);

            foreach ($request->file('images') ?? [] as $image) {
                ProductGallery::create([
                    'product_id' => $product->id,
                    'path'       => $this->storeMedia($image, self::GALLERY_DIR),
                ]);
            }

            if ($csv = $request->file('parameter')) {
                $this->importParameters($product, $csv, $request->parameterRows());
            }
        });

        $this->forgetProductCaches();

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $attributes = [
            'name'        => $request->validated('name'),
            'descreption' => $request->validated('shortdescreption'),
            'family_id'   => $request->validated('cat_id'),
            'link'        => $request->validated('link') ?? '',
        ];

        $oldPhoto = $product->getRawOriginal('photo');

        if ($file = $request->file('background')) {
            $attributes['photo'] = $this->storeMedia($file, self::PHOTO_DIR);
        }

        $product->update($attributes);

        if (isset($attributes['photo'])) {
            $this->deleteMedia($oldPhoto, self::PHOTO_DIR);
        }

        $this->forgetProductCaches($product);

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): Response
    {
        $photo = $product->getRawOriginal('photo');
        $galleryPaths = ProductGallery::where('product_id', $product->id)
            ->get()
            ->map(fn (ProductGallery $image) => $image->getRawOriginal('path'));

        ProductGallery::where('product_id', $product->id)->delete();
        ProductParameter::where('product_id', $product->id)->delete();
        $product->categories()->detach();
        $product->subCategories()->detach();
        $product->delete();

        // The old delete() left every uploaded file behind.
        $this->deleteMedia($photo, self::PHOTO_DIR);

        foreach ($galleryPaths as $path) {
            $this->deleteMedia($path, self::GALLERY_DIR);
        }

        $this->forgetProductCaches($product);

        return response()->noContent();
    }

    public function feature(Product $product): RedirectResponse
    {
        $product->update(['is_featured' => 1]);
        $this->forgetProductCaches($product);

        return redirect()->back()->with('status', 'Product featured.');
    }

    public function unfeature(Product $product): RedirectResponse
    {
        $product->update(['is_featured' => 0]);
        $this->forgetProductCaches($product);

        return redirect()->back()->with('status', 'Product unfeatured.');
    }

    // ---- card validity -------------------------------------------------
    //
    // These query a `card_number` column that is not in any migration, so they
    // only work if the column was added to the database by hand. Left in place
    // rather than removed, but they are almost certainly dead.

    public function checkValidity(string $cardNumber, int $storeId): JsonResponse
    {
        $exists = Product::where('card_number', $cardNumber)
            ->where('store_id', $storeId)
            ->exists();

        return response()->json(['valid' => $exists ? 1 : 0]);
    }

    public function checkValidityPair(string $cardNumber, int $storeId, string $cardNumber1): JsonResponse
    {
        $products = Product::whereIn('card_number', [$cardNumber, $cardNumber1])
            ->where('store_id', $storeId)
            ->get();

        return response()->json(['valid' => $products->count(), 'products' => $products]);
    }

    // ---- internals -----------------------------------------------------

    /**
     * One server-side page of a grid feed. The full mapped collection is still
     * cached under a single key — kept in sync by forgetProductCaches() — so the
     * invalidation contract is unchanged; the grid just receives the requested
     * slice plus the unfiltered `total` Kendo needs to size its pager.
     *
     * HasMediaUrls resolves `photo` in getAttribute(), which toArray() bypasses,
     * so rows are cached as plain arrays already carrying absolute URLs.
     */
    private function pagedRows(string $cacheKey, callable $query, int $page, int $pageSize): JsonResponse
    {
        $rows = Cache::remember($cacheKey, now()->addMinutes(10), fn () => $query()
            ->get()
            ->map(fn (Product $product): array => $this->toGridRow($product))
            ->all());

        return response()->json([
            'data'  => array_values(array_slice($rows, ($page - 1) * $pageSize, $pageSize)),
            'total' => count($rows),
        ]);
    }

    private function toGridRow(Product $product): array
    {
        return [
            'id'          => $product->id,
            'name'        => $product->name,
            'photo'       => $product->photo,
            'link'        => $product->link,
            'is_featured' => $product->is_featured,
            'family_id'   => $product->family_id,
            'descreption' => $product->descreption,
        ];
    }

    /**
     * Write the parameter rows the request already parsed and validated.
     *
     * Parsing lives in StoreProductRequest::parameterRows(), which drops the
     * blank and comma-only filler lines spreadsheet exports leave behind and
     * rejects the upload outright when nothing usable is left — so a filler CSV
     * fails before the product and its images are ever written, and every row
     * reaching this point is complete.
     *
     * @param array<int, array<string, string>> $rows
     */
    private function importParameters(Product $product, UploadedFile $csv, array $rows): void
    {
        foreach ($rows as $row) {
            ProductParameter::create($row + ['product_id' => $product->id]);
        }

        // Keep a copy for reference, under a generated name rather than the
        // uploaded one, which was user-controlled.
        $csv->move(public_path(self::CSV_DIR), Str::uuid() . '.' . $csv->getClientOriginalExtension());
    }

    private function forgetProductCaches(?Product $product = null): void
    {
        foreach (['products', 'products_all', 'products_featured'] as $key) {
            Cache::forget($key);
        }

        if ($product) {
            Cache::forget('product_' . $product->id);
        }
    }
}
