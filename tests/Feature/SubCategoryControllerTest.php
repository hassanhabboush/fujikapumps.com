<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\Concerns\ActsAsAdmin;
use Tests\Concerns\CleansUploadDirectory;
use Tests\TestCase;

class SubCategoryControllerTest extends TestCase
{
    use ActsAsAdmin, CleansUploadDirectory, RefreshDatabase;

    protected function uploadDirectory(): string
    {
        return 'categorybackground';
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

    public function test_guests_cannot_reach_the_sub_category_screen(): void
    {
        $this->get('/sub_categories')->assertRedirect('/login');
    }

    public function test_data_returns_sub_categories(): void
    {
        $sub = SubCategory::factory()->create(['english_name' => 'Fans']);

        $this->actingAs($this->admin())
            ->getJson('/sub_categories/data')
            ->assertOk()
            ->assertJsonPath('data.0.id', $sub->id)
            ->assertJsonPath('data.0.english_name', 'Fans');
    }

    /**
     * This endpoint had no route at all before the refactor, so the screen
     * that calls it was fetching a 404.
     */
    public function test_by_category_returns_only_that_categorys_sub_categories(): void
    {
        $category = Category::factory()->create();
        $mine = SubCategory::factory()->create(['english_name' => 'Mine']);
        SubCategory::factory()->create(['english_name' => 'Other']);

        $mine->categories()->attach($category->id);

        $this->actingAs($this->admin())
            ->getJson('/sub_categories/by-category/' . $category->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.english_name', 'Mine');
    }

    public function test_data_returns_only_the_requested_page_with_a_total(): void
    {
        SubCategory::factory()->count(12)->create();

        $this->actingAs($this->admin())
            ->getJson('/sub_categories/data?page=1&pageSize=8')
            ->assertOk()
            ->assertJsonCount(8, 'data')
            ->assertJsonPath('total', 12);

        $this->actingAs($this->admin())
            ->getJson('/sub_categories/data?page=2&pageSize=8')
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonPath('total', 12);
    }

    public function test_by_category_paginates_and_totals_only_matches(): void
    {
        $category = Category::factory()->create();

        SubCategory::factory()->count(10)->create()
            ->each(fn (SubCategory $sub) => $sub->categories()->attach($category->id));
        // Noise that must not count toward the total.
        SubCategory::factory()->count(3)->create();

        $this->actingAs($this->admin())
            ->getJson('/sub_categories/by-category/' . $category->id . '?page=2&pageSize=8')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('total', 10);
    }

    public function test_show_includes_the_linked_category_ids(): void
    {
        $category = Category::factory()->create();
        $sub = SubCategory::factory()->create();
        $sub->categories()->attach($category->id);

        $this->actingAs($this->admin())
            ->getJson('/sub_categories/' . $sub->id)
            ->assertOk()
            ->assertJsonPath('data.sub', (string) $category->id);
    }

    public function test_store_creates_a_sub_category_and_links_categories(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->post('/sub_categories', [
                'name'       => 'Fans',
                'cat_id'     => [$category->id],
                'background' => UploadedFile::fake()->image('bg.jpg'),
            ])
            ->assertRedirect();

        $sub = SubCategory::firstWhere('english_name', 'Fans');

        $this->assertNotNull($sub);
        $this->assertTrue($sub->categories->contains($category->id));
        $this->assertFileExists(
            public_path('categorybackground/' . basename($sub->getRawOriginal('background')))
        );
    }

    public function test_store_requires_a_category(): void
    {
        $this->actingAs($this->admin())
            ->post('/sub_categories', [
                'name'       => 'Fans',
                'background' => UploadedFile::fake()->image('bg.jpg'),
            ])
            ->assertSessionHasErrors('cat_id');

        $this->assertDatabaseCount('sub_category', 0);
    }

    public function test_update_rewrites_the_category_links(): void
    {
        $old = Category::factory()->create();
        $new = Category::factory()->create();
        $sub = SubCategory::factory()->create(['english_name' => 'Fans']);
        $sub->categories()->attach($old->id);

        $this->actingAs($this->admin())
            ->put('/sub_categories/' . $sub->id, [
                'name'   => 'Ceiling Fans',
                'cat_id' => [$new->id],
            ])
            ->assertRedirect();

        $sub->refresh()->load('categories');

        $this->assertSame('Ceiling Fans', $sub->english_name);
        $this->assertFalse($sub->categories->contains($old->id));
        $this->assertTrue($sub->categories->contains($new->id));
    }

    public function test_writes_invalidate_the_cached_tree(): void
    {
        $category = Category::factory()->create();
        Cache::put('headerCategories', 'stale', now()->addHour());

        // attach() fires no model events, so the controller forgets by hand.
        $this->actingAs($this->admin())->post('/sub_categories', [
            'name'       => 'Fans',
            'cat_id'     => [$category->id],
            'background' => UploadedFile::fake()->image('bg.jpg'),
        ]);

        $this->assertNull(Cache::get('headerCategories'));
    }

    public function test_destroy_removes_the_row_its_links_and_its_file(): void
    {
        $file = public_path('categorybackground/doomed-sub.jpg');
        File::put($file, 'jpg');

        $category = Category::factory()->create();
        $sub = SubCategory::factory()->create([
            'background' => 'public/categorybackground/doomed-sub.jpg',
        ]);
        $sub->categories()->attach($category->id);

        $this->actingAs($this->admin())
            ->delete('/sub_categories/' . $sub->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('sub_category', ['id' => $sub->id]);
        $this->assertDatabaseMissing('category_subcategory', ['subcategory_id' => $sub->id]);
        $this->assertFileDoesNotExist($file);
    }
}
