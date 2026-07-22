<?php

namespace Tests\Feature;

use App\Models\SubCategory;
use App\Models\SubCategory1;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\Concerns\ActsAsAdmin;
use Tests\Concerns\CleansUploadDirectory;
use Tests\TestCase;

class SubCategory1ControllerTest extends TestCase
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

    public function test_guests_cannot_reach_the_screen(): void
    {
        $this->get('/sub_categories1')->assertRedirect('/login');
    }

    public function test_data_returns_rows(): void
    {
        $sub = SubCategory1::factory()->create(['english_name' => 'Ceiling']);

        $this->actingAs($this->admin())
            ->getJson('/sub_categories1/data')
            ->assertOk()
            ->assertJsonPath('data.0.id', $sub->id)
            ->assertJsonPath('data.0.english_name', 'Ceiling');
    }

    public function test_by_parent_returns_only_that_parents_children(): void
    {
        $parent = SubCategory::factory()->create();
        $mine = SubCategory1::factory()->create(['english_name' => 'Mine']);
        SubCategory1::factory()->create(['english_name' => 'Other']);

        $mine->parentSubCategories()->attach($parent->id);

        $this->actingAs($this->admin())
            ->getJson('/sub_categories1/by-parent/' . $parent->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.english_name', 'Mine');
    }

    public function test_count_by_name_reports_duplicates(): void
    {
        SubCategory1::factory()->create(['english_name' => 'Ceiling']);

        $this->actingAs($this->admin())
            ->getJson('/sub_categories1/count-by-name/Ceiling')
            ->assertOk()
            ->assertJsonPath('data', 1);
    }

    public function test_store_creates_a_row_and_links_parents(): void
    {
        $parent = SubCategory::factory()->create();

        $this->actingAs($this->admin())
            ->post('/sub_categories1', [
                'name'       => 'Ceiling',
                'cat_id'     => [$parent->id],
                'background' => UploadedFile::fake()->image('bg.jpg'),
            ])
            ->assertRedirect();

        $sub = SubCategory1::firstWhere('english_name', 'Ceiling');

        $this->assertNotNull($sub);
        $this->assertTrue($sub->parentSubCategories->contains($parent->id));
    }

    public function test_store_rejects_an_unknown_parent(): void
    {
        $this->actingAs($this->admin())
            ->post('/sub_categories1', [
                'name'       => 'Ceiling',
                'cat_id'     => [999999],
                'background' => UploadedFile::fake()->image('bg.jpg'),
            ])
            ->assertSessionHasErrors('cat_id.0');

        $this->assertDatabaseCount('sub_category_1', 0);
    }

    public function test_update_rewrites_the_parent_links(): void
    {
        $old = SubCategory::factory()->create();
        $new = SubCategory::factory()->create();
        $sub = SubCategory1::factory()->create(['english_name' => 'Ceiling']);
        $sub->parentSubCategories()->attach($old->id);

        $this->actingAs($this->admin())
            ->put('/sub_categories1/' . $sub->id, [
                'name'   => 'Wall',
                'cat_id' => [$new->id],
            ])
            ->assertRedirect();

        $sub->refresh()->load('parentSubCategories');

        $this->assertSame('Wall', $sub->english_name);
        $this->assertFalse($sub->parentSubCategories->contains($old->id));
        $this->assertTrue($sub->parentSubCategories->contains($new->id));
    }

    public function test_writes_invalidate_the_cached_tree(): void
    {
        $parent = SubCategory::factory()->create();
        Cache::put('headerCategories', 'stale', now()->addHour());

        $this->actingAs($this->admin())->post('/sub_categories1', [
            'name'       => 'Ceiling',
            'cat_id'     => [$parent->id],
            'background' => UploadedFile::fake()->image('bg.jpg'),
        ]);

        $this->assertNull(Cache::get('headerCategories'));
    }

    public function test_destroy_removes_the_row_its_links_and_its_file(): void
    {
        $file = public_path('categorybackground/doomed-sub1.jpg');
        File::put($file, 'jpg');

        $parent = SubCategory::factory()->create();
        $sub = SubCategory1::factory()->create([
            'background' => 'public/categorybackground/doomed-sub1.jpg',
        ]);
        $sub->parentSubCategories()->attach($parent->id);

        $this->actingAs($this->admin())
            ->delete('/sub_categories1/' . $sub->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('sub_category_1', ['id' => $sub->id]);
        $this->assertDatabaseMissing('subcategory_subcategory', ['subcategory_id' => $sub->id]);
        $this->assertFileDoesNotExist($file);
    }
}
