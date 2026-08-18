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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    use HandlesMediaUploads;

    private const PHOTO_DIR   = 'productbackground';
    private const GALLERY_DIR = 'productimage';
    private const CSV_DIR     = 'productcsv';

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

    public function data(ProductGridRequest $request): JsonResponse
    {
        return $this->pagedRows(fn () => $this->gridQuery(), $request);
    }

    public function featuredData(ProductGridRequest $request): JsonResponse
    {
        return $this->pagedRows(fn () => $this->gridQuery()->where('is_featured', 1), $request);
    }

    public function byCategory(ProductGridRequest $request, int $id): JsonResponse
    {
        return $this->pagedRows(
            fn () => $this->gridQuery()->whereHas('categories', fn ($q) => $q->where('categories.id', $id)),
            $request
        );
    }

    public function bySubCategory(ProductGridRequest $request, int $id): JsonResponse
    {
        return $this->pagedRows(
            fn () => $this->gridQuery()->whereHas('subCategories', fn ($q) => $q->where('sub_category.id', $id)),
            $request
        );
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json(['data' => [$this->toGridRow($product)]]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
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
        $galleryPaths = DB::table('product_gallery')
            ->where('product_id', $product->id)
            ->pluck('path');

        DB::transaction(function () use ($product): void {
            DB::table('product_gallery')->where('product_id', $product->id)->delete();
            DB::table('product_parameter')->where('product_id', $product->id)->delete();
            if (Schema::hasTable('product_category')) {
                DB::table('product_category')->where('product_id', $product->id)->delete();
            }
            if (Schema::hasTable('product_subcategory')) {
                DB::table('product_subcategory')->where('product_id', $product->id)->delete();
            }
            $product->delete();
        });

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

    private function gridQuery()
    {
        return Product::query()
            ->select('id', 'name', 'photo', 'link', 'is_featured', 'family_id', 'descreption')
            ->orderBy('id');
    }

    private function pagedRows(callable $query, ProductGridRequest $request): JsonResponse
    {
        $builder = $query();
        $total = (clone $builder)->count();
        $rows = $builder->forPage($request->pageNumber(), $request->perPage())
            ->get()
            ->map(fn (Product $product): array => $this->toGridRow($product))
            ->all();

        return response()->json(['data' => $rows, 'total' => $total]);
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

    private function importParameters(Product $product, UploadedFile $csv, array $rows): void
    {
        foreach ($rows as $row) {
            ProductParameter::create($row + ['product_id' => $product->id]);
        }

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
