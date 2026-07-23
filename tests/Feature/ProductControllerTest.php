<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Family;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductParameter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\Concerns\ActsAsAdmin;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use ActsAsAdmin, RefreshDatabase;

    /** @var array<int, string> */
    private array $dirs = ['productbackground', 'productimage', 'productcsv'];

    /** @var array<string, array<int, string>> */
    private array $before = [];

    protected function setUp(): void
    {
        parent::setUp();

        foreach ($this->dirs as $dir) {
            File::ensureDirectoryExists(public_path($dir));
            $this->before[$dir] = $this->filesIn($dir);
        }
    }

    protected function tearDown(): void
    {
        // Only sweep what this test added — these directories hold real assets.
        foreach ($this->before as $dir => $existing) {
            foreach (array_diff($this->filesIn($dir), $existing) as $path) {
                File::delete($path);
            }
        }

        parent::tearDown();
    }

    /** @return array<int, string> */
    private function filesIn(string $dir): array
    {
        return array_map(fn ($f) => $f->getPathname(), File::files(public_path($dir)));
    }

    public function test_guests_cannot_reach_the_product_screen(): void
    {
        $this->get('/products')->assertRedirect('/login');
    }

    /**
     * The action column embedded an unescaped double quote inside the Kendo
     * template's own double-quoted string, so the whole <script> block was a
     * syntax error and the grid silently rendered nothing.
     */
    public function test_product_grids_emit_parsable_action_templates(): void
    {
        $family  = Family::factory()->create();
        $product = Product::factory()->create(['family_id' => $family->id]);
        $category = Category::factory()->create();
        $product->categories()->attach($category->id);

        $screens = [
            '/products',
            '/products/featured',
            '/products/by-category/' . $category->id,
            '/products/by-subcategory/1',
        ];

        foreach ($screens as $screen) {
            $html = $this->actingAs($this->admin())->get($screen)
                ->assertOk()
                ->getContent();

            $this->assertStringNotContainsString(
                'onclick=\'patchTo("',
                $html,
                $screen . ' emits an unescaped quote inside the Kendo template string.'
            );
            $this->assertStringContainsString('function patchTo(', $html, $screen);
            $this->assertStringContainsString('X-CSRF-TOKEN', $html, $screen);
        }
    }

    public function test_data_returns_products_with_resolved_photo_urls(): void
    {
        $product = Product::factory()->create([
            'name'  => 'Pump',
            'photo' => 'public/productbackground/sample.jpg',
        ]);

        $this->actingAs($this->admin())
            ->getJson('/products/data')
            ->assertOk()
            ->assertJsonPath('data.0.id', $product->id)
            ->assertJsonPath('data.0.name', 'Pump')
            ->assertJsonPath('data.0.photo', asset('productbackground/sample.jpg'));
    }

    public function test_featured_data_returns_only_featured_products(): void
    {
        Product::factory()->create(['name' => 'Plain', 'is_featured' => 0]);
        Product::factory()->create(['name' => 'Star', 'is_featured' => 1]);

        $this->actingAs($this->admin())
            ->getJson('/products/featured/data')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Star');
    }

    public function test_by_category_returns_only_that_categorys_products(): void
    {
        $category = Category::factory()->create();
        $mine = Product::factory()->create(['name' => 'Mine']);
        Product::factory()->create(['name' => 'Other']);

        $mine->categories()->attach($category->id);

        $this->actingAs($this->admin())
            ->getJson('/products/by-category/' . $category->id . '/data')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Mine');
    }

    public function test_store_creates_a_product_with_gallery_images(): void
    {
        $family = Family::factory()->create();

        $this->actingAs($this->admin())
            ->post('/products', [
                'name'             => 'Pump',
                'shortdescreption' => 'A pump',
                'link'             => 'https://example.test',
                'cat_id'           => $family->id,
                'background'       => UploadedFile::fake()->image('pump.jpg'),
                'images'           => [
                    UploadedFile::fake()->image('g1.jpg'),
                    UploadedFile::fake()->image('g2.jpg'),
                ],
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::firstWhere('name', 'Pump');

        $this->assertNotNull($product);
        $this->assertSame(2, ProductGallery::where('product_id', $product->id)->count());
        $this->assertFileExists(
            public_path('productbackground/' . basename($product->getRawOriginal('photo')))
        );
    }

    /**
     * The old importer moved the CSV into public/ under the uploader's own
     * filename and then reopened it through a relative path.
     */
    public function test_store_imports_parameters_from_the_csv(): void
    {
        $family = Family::factory()->create();

        $csv = "Model,SerialNumber,PowerKw,PowerHp,q,h,v,Discharge,Hertz,Material,RPM,link\n"
             . "M-1,SN-1,1.5,2,10,20,230,50mm,50,Steel,1450,https://example.test\n"
             . "M-2,SN-2,2.5,3,11,21,400,60mm,60,Iron,2900,https://example.test\n";

        $this->actingAs($this->admin())
            ->post('/products', [
                'name'       => 'Pump',
                'cat_id'     => $family->id,
                'background' => UploadedFile::fake()->image('pump.jpg'),
                'parameter'  => UploadedFile::fake()->createWithContent('params.csv', $csv),
            ])
            ->assertRedirect();

        $product = Product::firstWhere('name', 'Pump');

        // Header row skipped, two data rows imported.
        $this->assertSame(2, ProductParameter::where('product_id', $product->id)->count());
        $this->assertDatabaseHas('product_parameter', [
            'product_id' => $product->id,
            'Model'      => 'M-1',
            'RPM'        => '1450',
        ]);
    }

    /**
     * An xlsx used to pass validation and reach fgetcsv(), which fed the raw
     * zip bytes into product_parameter until MySQL rejected them with a 500.
     */
    public function test_store_rejects_a_binary_parameter_file(): void
    {
        $family = Family::factory()->create();

        // PK\x03\x04 header — what an .xlsx actually is on disk.
        $workbook = "PK\x03\x04\x14\x00\x06\x00\x08\x00\x00\x00!\x00\xCAr\x96\xA0\x11V\x02\x00";

        $this->actingAs($this->admin())
            ->post('/products', [
                'name'       => 'Pump',
                'cat_id'     => $family->id,
                'background' => UploadedFile::fake()->image('pump.jpg'),
                'parameter'  => UploadedFile::fake()->createWithContent('params.xlsx', $workbook),
            ])
            ->assertSessionHasErrors('parameter');

        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('product_parameter', 0);
    }

    /** A binary file renamed to .csv must not slip past the extension check. */
    public function test_store_rejects_a_binary_file_renamed_to_csv(): void
    {
        $family = Family::factory()->create();

        $this->actingAs($this->admin())
            ->post('/products', [
                'name'       => 'Pump',
                'cat_id'     => $family->id,
                'background' => UploadedFile::fake()->image('pump.jpg'),
                'parameter'  => UploadedFile::fake()->createWithContent(
                    'params.csv',
                    "Model,SerialNumber\n\xCAr\x96\xA0\x11V,\xFF\xFE\x00"
                ),
            ])
            ->assertSessionHasErrors('parameter');

        $this->assertDatabaseCount('products', 0);
    }

    public function test_store_rejects_a_missing_name_and_an_unknown_family(): void
    {
        $this->actingAs($this->admin())
            ->post('/products', [
                'cat_id'     => 999999,
                'background' => UploadedFile::fake()->image('p.jpg'),
            ])
            ->assertSessionHasErrors(['name', 'cat_id']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_update_changes_the_product(): void
    {
        $family = Family::factory()->create();
        $product = Product::factory()->create(['name' => 'Pump']);

        $this->actingAs($this->admin())
            ->put('/products/' . $product->id, [
                'name'   => 'Big Pump',
                'cat_id' => $family->id,
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertSame('Big Pump', $product->refresh()->name);
    }

    public function test_feature_and_unfeature_use_patch(): void
    {
        $product = Product::factory()->create(['is_featured' => 0]);

        $this->actingAs($this->admin())
            ->patch('/products/' . $product->id . '/feature')
            ->assertRedirect();
        $this->assertSame(1, (int) $product->refresh()->is_featured);

        $this->actingAs($this->admin())
            ->patch('/products/' . $product->id . '/unfeature')
            ->assertRedirect();
        $this->assertSame(0, (int) $product->refresh()->is_featured);
    }

    public function test_destroy_removes_the_product_its_children_and_its_files(): void
    {
        $photo = public_path('productbackground/doomed-product.jpg');
        $galleryFile = public_path('productimage/doomed-gallery.jpg');
        File::put($photo, 'jpg');
        File::put($galleryFile, 'jpg');

        $product = Product::factory()->create([
            'photo' => 'public/productbackground/doomed-product.jpg',
        ]);
        ProductGallery::factory()->create([
            'product_id' => $product->id,
            'path'       => 'public/productimage/doomed-gallery.jpg',
        ]);
        ProductParameter::factory()->create(['product_id' => $product->id]);

        $this->actingAs($this->admin())
            ->delete('/products/' . $product->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('product_gallery', ['product_id' => $product->id]);
        $this->assertDatabaseMissing('product_parameter', ['product_id' => $product->id]);
        $this->assertFileDoesNotExist($photo);
        $this->assertFileDoesNotExist($galleryFile);
    }

    public function test_writes_invalidate_the_cached_lists(): void
    {
        $family = Family::factory()->create();

        $this->actingAs($this->admin())->getJson('/products/data')->assertOk();
        $this->assertNotNull(Cache::get('products_all'));

        $this->actingAs($this->admin())->post('/products', [
            'name'       => 'Pump',
            'cat_id'     => $family->id,
            'background' => UploadedFile::fake()->image('p.jpg'),
        ]);

        $this->assertNull(Cache::get('products_all'));
    }
}
