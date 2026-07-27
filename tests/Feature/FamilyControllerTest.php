<?php

namespace Tests\Feature;

use App\Models\Family;
use App\Models\FamilySubcategory;
use App\Models\SubCategory1;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\Concerns\ActsAsAdmin;
use Tests\Concerns\CleansUploadDirectory;
use Tests\TestCase;

class FamilyControllerTest extends TestCase
{
    use ActsAsAdmin, CleansUploadDirectory, RefreshDatabase;

    private const BACKGROUND_DIR = 'categorybackground';

    protected function uploadDirectory(): string
    {
        return self::BACKGROUND_DIR;
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

    public function test_guests_cannot_reach_the_family_screen(): void
    {
        $this->get('/families')->assertRedirect('/login');
    }

    public function test_data_returns_families(): void
    {
        $family = Family::factory()->create(['english_name' => 'Ceiling']);

        $this->actingAs($this->admin())
            ->getJson('/families/data')
            ->assertOk()
            ->assertJsonPath('data.0.id', $family->id)
            ->assertJsonPath('data.0.english_name', 'Ceiling');
    }

    public function test_data_returns_only_the_requested_page_with_a_total(): void
    {
        Family::factory()->count(12)->create();

        $this->actingAs($this->admin())
            ->getJson('/families/data?page=1&pageSize=8')
            ->assertOk()
            ->assertJsonCount(8, 'data')
            ->assertJsonPath('total', 12);

        $this->actingAs($this->admin())
            ->getJson('/families/data?page=2&pageSize=8')
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonPath('total', 12);
    }

    public function test_list_by_category_paginates_and_totals_only_matches(): void
    {
        $sub = SubCategory1::factory()->create();

        Family::factory()->count(10)->create()->each(function (Family $family) use ($sub): void {
            FamilySubcategory::create(['family_id' => $family->id, 'sub_category_id' => $sub->id]);
        });
        // Noise that must not count toward the total.
        Family::factory()->count(3)->create();

        $this->actingAs($this->admin())
            ->getJson('/families/by-subcategory/' . $sub->id . '?page=2&pageSize=8')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('total', 10);
    }

    public function test_store_creates_a_family_and_links_sub_categories(): void
    {
        $sub = SubCategory1::factory()->create();

        $this->actingAs($this->admin())
            ->post('/families', [
                'name'       => 'Ceiling',
                'link'       => 'https://example.test',
                'cat_id'     => [$sub->id],
                'background' => UploadedFile::fake()->image('bg.jpg'),
            ])
            ->assertRedirect();

        $family = Family::firstWhere('english_name', 'Ceiling');

        $this->assertNotNull($family);
        $this->assertDatabaseHas('family_subcategory', [
            'family_id'       => $family->id,
            'sub_category_id' => $sub->id,
        ]);

        $stored = $family->getRawOriginal('background');

        $this->assertStringStartsWith('public/' . self::BACKGROUND_DIR . '/', $stored);
        $this->assertFileExists(public_path(self::BACKGROUND_DIR . '/' . basename($stored)));
    }

    public function test_store_requires_at_least_one_sub_category(): void
    {
        $this->actingAs($this->admin())
            ->post('/families', [
                'name'       => 'Ceiling',
                'background' => UploadedFile::fake()->image('bg.jpg'),
            ])
            ->assertSessionHasErrors('cat_id');

        $this->assertDatabaseCount('family', 0);
    }

    public function test_store_rejects_an_unknown_sub_category(): void
    {
        $this->actingAs($this->admin())
            ->post('/families', [
                'name'       => 'Ceiling',
                'cat_id'     => [999999],
                'background' => UploadedFile::fake()->image('bg.jpg'),
            ])
            ->assertSessionHasErrors('cat_id.0');

        $this->assertDatabaseCount('family', 0);
    }

    public function test_update_rewrites_the_sub_category_links(): void
    {
        $family = Family::factory()->create(['english_name' => 'Ceiling']);
        $old = SubCategory1::factory()->create();
        $new = SubCategory1::factory()->create();

        FamilySubcategory::create([
            'family_id'       => $family->id,
            'sub_category_id' => $old->id,
        ]);

        $this->actingAs($this->admin())
            ->put('/families/' . $family->id, [
                'name'   => 'Wall',
                'cat_id' => [$new->id],
            ])
            ->assertRedirect();

        $this->assertSame('Wall', $family->refresh()->english_name);
        $this->assertDatabaseMissing('family_subcategory', [
            'family_id'       => $family->id,
            'sub_category_id' => $old->id,
        ]);
        $this->assertDatabaseHas('family_subcategory', [
            'family_id'       => $family->id,
            'sub_category_id' => $new->id,
        ]);
    }

    /**
     * The old update() stored on the public disk (storage/app/public), so an
     * updated family only rendered through the public/storage symlink and showed
     * a broken thumbnail wherever that symlink does not exist.
     */
    public function test_update_stores_the_new_background_under_public(): void
    {
        $family = Family::factory()->create();
        $sub = SubCategory1::factory()->create();

        $this->actingAs($this->admin())
            ->put('/families/' . $family->id, [
                'name'       => 'Ceiling',
                'cat_id'     => [$sub->id],
                'background' => UploadedFile::fake()->image('new.jpg'),
            ])
            ->assertRedirect();

        $stored = $family->refresh()->getRawOriginal('background');

        $this->assertStringStartsWith('public/' . self::BACKGROUND_DIR . '/', $stored);
        $this->assertFileExists(public_path(self::BACKGROUND_DIR . '/' . basename($stored)));
        $this->assertFileDoesNotExist(storage_path('app/public/' . $stored));
    }

    public function test_update_removes_the_replaced_file_and_its_webp_sibling(): void
    {
        $jpg  = public_path(self::BACKGROUND_DIR . '/old.jpg');
        $webp = public_path(self::BACKGROUND_DIR . '/old.webp');
        File::put($jpg, 'jpg');
        File::put($webp, 'webp');

        $family = Family::factory()->create([
            'background' => 'public/' . self::BACKGROUND_DIR . '/old.jpg',
        ]);
        $sub = SubCategory1::factory()->create();

        $this->actingAs($this->admin())
            ->put('/families/' . $family->id, [
                'name'       => 'Ceiling',
                'cat_id'     => [$sub->id],
                'background' => UploadedFile::fake()->image('new.jpg'),
            ])
            ->assertRedirect();

        $this->assertNotSame(
            'public/' . self::BACKGROUND_DIR . '/old.jpg',
            $family->refresh()->getRawOriginal('background')
        );
        $this->assertFileDoesNotExist($jpg);
        $this->assertFileDoesNotExist($webp);
    }

    public function test_update_without_a_file_keeps_the_current_background(): void
    {
        $family = Family::factory()->create([
            'background' => 'public/' . self::BACKGROUND_DIR . '/keep.jpg',
        ]);
        $sub = SubCategory1::factory()->create();

        $this->actingAs($this->admin())
            ->put('/families/' . $family->id, [
                'name'   => 'Ceiling',
                'cat_id' => [$sub->id],
            ])
            ->assertRedirect();

        $this->assertSame(
            'public/' . self::BACKGROUND_DIR . '/keep.jpg',
            $family->refresh()->getRawOriginal('background')
        );
    }

    public function test_writes_invalidate_the_cached_tree(): void
    {
        $sub = SubCategory1::factory()->create();

        $this->actingAs($this->admin())->getJson('/families/data')->assertOk();
        Cache::put('headerCategories', 'stale', now()->addHour());
        $this->assertNotNull(Cache::get('families'));

        // Pivot writes fire no model events, so the controller forgets both
        // keys by hand after touching family_subcategory.
        $this->actingAs($this->admin())->post('/families', [
            'name'       => 'Ceiling',
            'cat_id'     => [$sub->id],
            'background' => UploadedFile::fake()->image('bg.jpg'),
        ]);

        $this->assertNull(Cache::get('families'));
        $this->assertNull(Cache::get('headerCategories'));
    }

    public function test_destroy_removes_the_family_its_links_and_its_file(): void
    {
        $family = Family::factory()->create();
        $sub = SubCategory1::factory()->create();

        FamilySubcategory::create([
            'family_id'       => $family->id,
            'sub_category_id' => $sub->id,
        ]);

        $file = public_path(self::BACKGROUND_DIR . '/' . basename($family->getRawOriginal('background')));
        File::put($file, 'bg');

        $this->actingAs($this->admin())
            ->delete('/families/' . $family->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('family', ['id' => $family->id]);
        $this->assertDatabaseMissing('family_subcategory', ['family_id' => $family->id]);
        $this->assertFileDoesNotExist($file);
    }

    /**
     * The bundled Kendo RemoteTransport deep-extends transport.destroy, so a
     * function value is silently dropped and the row only vanishes client-side.
     */
    public function test_the_grid_declares_destroy_as_a_transport_object(): void
    {
        $html = $this->actingAs($this->admin())->get('/families')->assertOk()->getContent();

        $this->assertStringNotContainsString('destroy: function(options)', $html);
        $this->assertStringContainsString('type: "DELETE"', $html);
    }

    /**
     * The grid deletes over AJAX; a 302 was followed with DELETE onto /families,
     * which has no such route, so the row came back as an error.
     */
    public function test_destroy_does_not_redirect(): void
    {
        $family = Family::factory()->create();

        $response = $this->actingAs($this->admin())->delete('/families/' . $family->id);

        $this->assertSame(204, $response->getStatusCode());
        $response->assertHeaderMissing('Location');
    }
}
