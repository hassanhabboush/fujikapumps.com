<?php

namespace Tests\Feature;

use App\Models\About;
use App\Models\Accessory;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Family;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductParameter;
use App\Models\Series;
use App\Models\SubCategory;
use App\Models\SubCategory1;
use App\Support\CatalogCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use Tests\TestCase;

class WebsiteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Both public landing pages index into $about[0]; the shared layout
        // dereferences the globally shared $contact row.
        About::create(['title1' => 'About Fujika', 'desc1' => 'Pumps.']);
        Contact::create(['address' => 'Riyadh', 'phone1' => '000', 'email' => 'info@example.com']);

        // AppServiceProvider shares $contact at boot, which happens before
        // RefreshDatabase has a row to find.
        Cache::forget('contact');
        $this->app['view']->share('contact', Contact::first());
    }

    public function test_home_page_renders(): void
    {
        Category::factory()->create(['english_name' => 'Commercial']);
        Product::factory()->create(['name' => 'Featured Pump', 'is_featured' => 1]);

        // home.blade never renders $products, but it is part of the view
        // contract, so assert on the data rather than the markup.
        $this->get('/')
            ->assertOk()
            ->assertViewHas('products', fn ($products) => $products->pluck('name')->contains('Featured Pump'))
            ->assertViewHasAll(['about', 'slider', 'category', 'sub_category', 'sub_category1', 'family', 'Hertz', 'dm', 'material', 'rpm', 'volt']);
    }

    public function test_about_and_contact_pages_render(): void
    {
        Category::factory()->create();

        $this->get('/about')->assertOk();
        $this->get('/contactus')->assertOk();
    }

    /**
     * The three pages used to cache different column sets under one 'category'
     * key, so whichever page warmed the cache first decided what the others got.
     */
    public function test_category_list_is_cached_once_with_a_complete_column_set(): void
    {
        Category::factory()->create(['english_name' => 'Commercial']);

        $this->get('/contactus')->assertOk();

        $cached = Cache::get(CatalogCache::qualify('categories'));

        $this->assertNotNull($cached);
        foreach (['id', 'english_name', 'logo', 'background', 'short_descreption', 'created_at', 'updated_at'] as $column) {
            $this->assertArrayHasKey($column, $cached->first()->getAttributes());
        }

        // The page that warmed it first must not starve the richer pages.
        $this->get('/about')->assertOk();
        $this->get('/')->assertOk();
    }

    /**
     * Public keys are namespaced, so they can no longer overwrite the admin
     * panel's 'categories' / 'accessories' entries, which hold other shapes.
     */
    public function test_public_cache_keys_do_not_collide_with_admin_keys(): void
    {
        Category::factory()->create();

        Cache::put('categories', 'admin-shape', now()->addHour());

        $this->get('/contactus')->assertOk();

        $this->assertSame('admin-shape', Cache::get('categories'));
    }

    public function test_saving_a_category_invalidates_the_public_catalog_cache(): void
    {
        $category = Category::factory()->create();

        $this->get('/contactus')->assertOk();
        $key = CatalogCache::qualify('categories');
        $this->assertNotNull(Cache::get($key));

        $category->update(['english_name' => 'Renamed']);

        // The namespace moved, so every catalog entry under the old stamp is dead.
        $this->assertNotSame($key, CatalogCache::qualify('categories'));

        $this->get('/contactus')
            ->assertViewHas('category', fn ($categories) => $categories->pluck('english_name')->contains('Renamed'));
    }

    /**
     * Paginated results used to be cached under a page-less key, which served
     * page 1 for every page number.
     */
    public function test_accessories_pagination_returns_distinct_pages(): void
    {
        Category::factory()->create();
        Accessory::factory()->count(20)->create();

        $first = Accessory::orderBy('id')->first();
        $last = Accessory::orderBy('id', 'desc')->first();

        $this->get('/1/7/accessories')
            ->assertOk()
            ->assertSee($first->name, false)
            ->assertDontSee($last->name, false);

        $this->get('/1/7/accessories?page=2')
            ->assertOk()
            ->assertSee($last->name, false);
    }

    public function test_series_of_a_family_paginates_past_the_first_page(): void
    {
        Category::factory()->create();
        $family = Family::factory()->create();
        Series::factory()->count(12)->create(['family_id' => $family->id]);

        $last = Series::orderBy('id', 'desc')->first();

        $this->get("/{$family->id}/5/series")->assertOk()->assertDontSee($last->english_name, false);
        $this->get("/{$family->id}/5/series?page=2")->assertOk()->assertSee($last->english_name, false);
    }

    public function test_category_page_lists_sub_categories_for_the_requested_category(): void
    {
        $category = Category::factory()->create();
        $subCategory = SubCategory::factory()->create();
        $subCategory1 = SubCategory1::factory()->create(['english_name' => 'Deep Well']);

        $category->subCategories()->attach($subCategory->id);
        $subCategory->subCategory1s()->attach($subCategory1->id);

        $this->get("/{$category->id}/2/pumps")
            ->assertOk()
            ->assertSee('Deep Well', false);
    }

    public function test_product_page_renders_the_product_and_its_gallery(): void
    {
        Category::factory()->create();
        $product = Product::factory()->create(['name' => 'FJK-100', 'descreption' => 'A very capable pump.']);
        ProductGallery::factory()->create(['product_id' => $product->id]);

        $this->get("/{$product->id}/6/fjk-100")
            ->assertOk()
            ->assertSee('A very capable pump.', false)
            // web.product indexes into $product, so the shape must stay list-like.
            ->assertViewHas('product', fn ($p) => $p[0]->name === 'FJK-100')
            ->assertViewHas('gallery', fn ($g) => $g->count() === 1);
    }

    public function test_unknown_product_returns_404_instead_of_a_server_error(): void
    {
        Category::factory()->create();

        $this->get('/999999/6/missing')->assertNotFound();
    }

    public function test_unknown_type_returns_404(): void
    {
        Category::factory()->create();

        $this->get('/1/99/whatever')->assertNotFound();
    }

    public function test_non_numeric_url_segments_return_404_not_a_server_error(): void
    {
        Category::factory()->create();

        $this->get('/abc/1/whatever')->assertNotFound();
        $this->get('/1/abc/whatever')->assertNotFound();
    }

    public function test_filter_matches_parameters_within_the_tolerance_band(): void
    {
        Category::factory()->create();
        $product = Product::factory()->create();

        // q = 10 → ±50%, so 8 is inside the band and 40 is outside.
        ProductParameter::factory()->create(['product_id' => $product->id, 'Model' => 'IN-BAND', 'q' => '8', 'h' => '10']);
        ProductParameter::factory()->create(['product_id' => $product->id, 'Model' => 'OUT-OF-BAND', 'q' => '40', 'h' => '10']);

        $this->get('/filter?q=10')
            ->assertOk()
            ->assertSee('IN-BAND', false)
            ->assertDontSee('OUT-OF-BAND', false);
    }

    public function test_filter_ignores_the_placeholder_option_values(): void
    {
        Category::factory()->create();
        $product = Product::factory()->create();
        ProductParameter::factory()->create(['product_id' => $product->id, 'Model' => 'ANY-RPM', 'RPM' => '2900']);

        $this->get('/filter?rpm=RPM&material=Material&dm=Size&hertz=Hertz&volt=Voltage')
            ->assertOk()
            ->assertSee('ANY-RPM', false);
    }

    public function test_filter_rejects_a_non_numeric_flow_rate(): void
    {
        Category::factory()->create();

        $this->get('/filter?q=abc')->assertSessionHasErrors('q');
    }

    public function test_filterpop_searches_products_and_does_not_cache_the_keyword(): void
    {
        Category::factory()->create();
        Product::factory()->create(['name' => 'Booster Set']);
        Product::factory()->create(['name' => 'Sump Pump']);

        $this->get('/filterpop?keyword=Booster')
            ->assertOk()
            ->assertSee('Booster Set', false)
            ->assertDontSee('Sump Pump', false);

        // A newly added match must show up immediately, not after the TTL.
        Product::factory()->create(['name' => 'Booster Twin']);

        $this->get('/filterpop?keyword=Booster')->assertSee('Booster Twin', false);
    }

    public function test_filterpop_searches_families_when_commercial_is_set(): void
    {
        Category::factory()->create();
        Family::factory()->create(['english_name' => 'Commercial Range']);

        $this->get('/filterpop?keyword=Commercial&commercial=1')
            ->assertOk()
            ->assertSee('Commercial Range', false);
    }

    /**
     * The body used to echo the site's own from-address back as "Email", so the
     * visitor's address never reached sales.
     */
    public function test_contact_form_includes_the_visitors_own_address(): void
    {
        $this->get('/sendemail?' . http_build_query([
            'name'    => 'Jane Buyer',
            'email'   => 'jane@example.com',
            'phone'   => '0100000000',
            'company' => 'Acme',
            'inquiry' => 'Need a pump.',
        ]))->assertRedirect();

        $messages = $this->sentMessages();
        $this->assertCount(1, $messages);

        $email = $messages->first()->getOriginalMessage();

        $this->assertStringContainsString('Email:jane@example.com', $email->getTextBody());
        $this->assertSame('jane@example.com', $email->getReplyTo()[0]->getAddress());
        $this->assertSame(config('mail.from.address'), $email->getFrom()[0]->getAddress());
        $this->assertSame(config('contact.receiver'), $email->getTo()[0]->getAddress());
    }

    public function test_contact_form_rejects_a_missing_email(): void
    {
        $this->get('/sendemail?name=Jane&inquiry=Hello')->assertSessionHasErrors('email');

        $this->assertCount(0, $this->sentMessages());
    }

    private function sentMessages(): Collection
    {
        return Mail::mailer()->getSymfonyTransport()->messages();
    }

    public function test_parameter_lookup_endpoints_return_distinct_non_empty_values(): void
    {
        $product = Product::factory()->create();
        ProductParameter::factory()->create(['product_id' => $product->id, 'v' => '220']);
        ProductParameter::factory()->create(['product_id' => $product->id, 'v' => '220']);
        ProductParameter::factory()->create(['product_id' => $product->id, 'v' => '']);

        $response = $this->get('web/getvolt/')->assertOk();

        $this->assertSame([['v' => '220']], $response->json('data'));
    }

    public function test_category_lookup_endpoint_returns_categories(): void
    {
        Category::factory()->create(['english_name' => 'Commercial']);

        $this->get('web/getcategory/')
            ->assertOk()
            ->assertJsonPath('data.0.english_name', 'Commercial');
    }
}
