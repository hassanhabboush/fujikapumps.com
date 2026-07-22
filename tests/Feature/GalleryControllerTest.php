<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\Concerns\ActsAsAdmin;
use Tests\Concerns\CleansUploadDirectory;
use Tests\TestCase;

class GalleryControllerTest extends TestCase
{
    use ActsAsAdmin, CleansUploadDirectory, RefreshDatabase;

    protected function uploadDirectory(): string
    {
        return 'productimage';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->snapshotUploadDirectory();
    }

    protected function tearDown(): void
    {
        $this->cleanUploadDirectory();
        parent::tearDown();
    }

    public function test_guests_cannot_reach_the_gallery_screen(): void
    {
        $product = Product::factory()->create();

        $this->get('/products/' . $product->id . '/gallery')->assertRedirect('/login');
    }

    public function test_data_returns_only_that_products_images(): void
    {
        $product = Product::factory()->create();
        $other = Product::factory()->create();

        ProductGallery::factory()->create([
            'product_id' => $product->id,
            'path'       => 'public/productimage/mine.jpg',
        ]);
        ProductGallery::factory()->create(['product_id' => $other->id]);

        $this->actingAs($this->admin())
            ->getJson('/products/' . $product->id . '/gallery/data')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.path', asset('productimage/mine.jpg'));
    }

    /**
     * The product used to come from the session, so an upload could land on
     * whichever product another tab had opened last.
     */
    public function test_store_files_the_image_against_the_product_in_the_url(): void
    {
        $product = Product::factory()->create();
        $other = Product::factory()->create();

        $this->actingAs($this->admin())
            ->withSession(['product_id' => $other->id])
            ->post('/products/' . $product->id . '/gallery', [
                'background' => UploadedFile::fake()->image('shot.jpg'),
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('product_gallery', 1);
        $this->assertDatabaseHas('product_gallery', ['product_id' => $product->id]);
        $this->assertDatabaseMissing('product_gallery', ['product_id' => $other->id]);
    }

    public function test_store_requires_an_image(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin())
            ->post('/products/' . $product->id . '/gallery', [])
            ->assertSessionHasErrors('background');

        $this->assertDatabaseCount('product_gallery', 0);
    }

    public function test_store_invalidates_the_cached_list(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin())
            ->getJson('/products/' . $product->id . '/gallery/data')->assertOk();
        $this->assertNotNull(Cache::get('gallery_' . $product->id));

        $this->actingAs($this->admin())
            ->post('/products/' . $product->id . '/gallery', [
                'background' => UploadedFile::fake()->image('shot.jpg'),
            ]);

        $this->assertNull(Cache::get('gallery_' . $product->id));
    }

    public function test_destroy_removes_the_row_and_its_file(): void
    {
        $file = public_path('productimage/doomed-gallery.jpg');
        File::put($file, 'jpg');

        $image = ProductGallery::factory()->create([
            'path' => 'public/productimage/doomed-gallery.jpg',
        ]);

        $this->actingAs($this->admin())
            ->delete('/gallery/' . $image->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('product_gallery', ['id' => $image->id]);
        $this->assertFileDoesNotExist($file);
    }
}
