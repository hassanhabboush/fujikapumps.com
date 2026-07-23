<?php

namespace App\Services;

use App\Models\About;
use App\Models\Accessory;
use App\Models\Category;
use App\Models\Family;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductParameter;
use App\Models\Series;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\SubCategory1;
use App\Models\Team;
use App\Support\CatalogCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Read model for the public site.
 *
 * Paginated results are deliberately never cached: a paginator captures the
 * page it was built for, so caching one under a page-less key served page 1
 * for every page number.
 */
class WebsiteCatalog
{
    /**
     * One column set for the flat category list, so every page that reads the
     * shared cache entry gets the same shape.
     */
    private const CATEGORY_COLUMNS = [
        'id', 'english_name', 'logo', 'background', 'short_descreption', 'created_at', 'updated_at',
    ];

    private const PER_PAGE = 9;

    public function sliders(): Collection
    {
        return CatalogCache::remember('sliders', fn () => Slider::all());
    }

    public function categories(): Collection
    {
        return CatalogCache::remember(
            'categories',
            fn () => Category::select(self::CATEGORY_COLUMNS)->get()
        );
    }

    /**
     * The paginated category tree every catalog page renders in its nav.
     */
    public function categoryTree(): LengthAwarePaginator
    {
        return Category::with('subCategories.subCategory1s.families')
            ->select(self::CATEGORY_COLUMNS)
            ->paginate(self::PER_PAGE);
    }

    public function subCategories(): Collection
    {
        return CatalogCache::remember(
            'sub_categories',
            fn () => SubCategory::select('id', 'english_name', 'background')->get()
        );
    }

    public function subCategories1(): Collection
    {
        return CatalogCache::remember(
            'sub_categories1',
            fn () => SubCategory1::select('id', 'english_name', 'background')->get()
        );
    }

    public function families(): Collection
    {
        return CatalogCache::remember(
            'families',
            fn () => Family::select('id', 'english_name', 'background', 'link')->get()
        );
    }

    public function about(): Collection
    {
        return CatalogCache::remember('about', fn () => About::get());
    }

    public function team(): Collection
    {
        return CatalogCache::remember('team', fn () => Team::all());
    }

    public function gallery(): Collection
    {
        return CatalogCache::remember('gallery', fn () => Gallery::all());
    }

    public function featuredProducts(): Collection
    {
        return CatalogCache::remember(
            'featured_products',
            fn () => Product::select('id', 'name', 'is_featured', 'descreption', 'photo', 'link')
                ->where('is_featured', 1)
                ->get()
        );
    }

    /**
     * The five parameter columns the home-page filter dropdowns are built from.
     */
    public function parameterOptions(): Collection
    {
        return CatalogCache::remember(
            'parameter_options',
            fn () => ProductParameter::select('Hertz', 'Discharge_diameter', 'Material', 'RPM', 'v')->get()
        );
    }

    /**
     * Distinct, non-empty values of one parameter column, for the filter API.
     */
    public function parameterValues(string $column): Collection
    {
        return CatalogCache::remember(
            'parameter_values.' . $column,
            fn () => ProductParameter::select($column)
                ->whereNotNull($column)
                ->where($column, '<>', '')
                ->distinct()
                ->get()
        );
    }

    /**
     * Third-level sub-categories reachable from a top-level category.
     */
    public function subCategories1OfCategory(int $id): Collection
    {
        return CatalogCache::remember(
            'category.' . $id . '.sub_categories1',
            fn () => SubCategory1::whereHas(
                'parentSubCategories',
                fn ($q) => $q->whereHas('categories', fn ($q2) => $q2->where('categories.id', $id))
            )->select('id', 'english_name', 'background')->get()
        );
    }

    public function subCategories1OfSubCategory(int $id): LengthAwarePaginator
    {
        return SubCategory::findOrFail($id)
            ->subCategory1s()
            ->select('sub_category_1.id', 'sub_category_1.english_name', 'sub_category_1.background')
            ->paginate(self::PER_PAGE);
    }

    public function seriesOfSubCategory1(int $id): Collection
    {
        return CatalogCache::remember(
            'sub_category1.' . $id . '.series',
            fn () => Series::whereHas(
                'family',
                fn ($q) => $q->whereHas('subCategory1s', fn ($q2) => $q2->where('sub_category_1.id', $id))
            )->select('id', 'link', 'english_name', 'photo', 'text1', 'text2', 'text3')->get()
        );
    }

    public function seriesOfFamily(int $id): LengthAwarePaginator
    {
        return Series::select('id', 'english_name', 'photo', 'link', 'text1', 'text2', 'text3')
            ->where('family_id', $id)
            ->paginate(self::PER_PAGE);
    }

    public function accessories(): LengthAwarePaginator
    {
        return Accessory::select('id', 'name', 'photo', 'link')->paginate(15);
    }

    public function product(int $id): Product
    {
        return Product::select('id', 'name', 'is_featured', 'photo', 'descreption', 'link')->findOrFail($id);
    }

    public function productGallery(int $id): Collection
    {
        return CatalogCache::remember(
            'product.' . $id . '.gallery',
            fn () => ProductGallery::select('path')->where('product_id', $id)->get()
        );
    }

    /**
     * Search results are not cached: the key would be attacker-controlled and
     * the file store would grow one entry per distinct keyword typed.
     */
    public function searchFamilies(?string $keyword): Collection
    {
        return Family::select('id', 'english_name as name', 'link')
            ->when($keyword !== null && $keyword !== '', fn ($q) => $q->where('english_name', 'like', '%' . $keyword . '%'))
            ->get();
    }

    public function searchProducts(?string $keyword): Collection
    {
        return Product::select('id', 'name', 'link')
            ->when($keyword !== null && $keyword !== '', fn ($q) => $q->where('name', 'like', '%' . $keyword . '%'))
            ->get();
    }
}
