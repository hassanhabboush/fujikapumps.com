<?php

namespace Tests\Feature;

use App\Models\Family;
use App\Models\FamilySubcategory;
use App\Models\SubCategory1;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\ActsAsAdmin;
use Tests\TestCase;

class FamilyControllerTest extends TestCase
{
    use ActsAsAdmin, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Family is the one entity already on the public disk rather than
        // public/<dir>, so a fake disk is enough here.
        Storage::fake('public');
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
        Storage::disk('public')->assertExists($family->getRawOriginal('background'));
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

        Storage::disk('public')->put($family->getRawOriginal('background'), 'bg');

        $this->actingAs($this->admin())
            ->delete('/families/' . $family->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('family', ['id' => $family->id]);
        $this->assertDatabaseMissing('family_subcategory', ['family_id' => $family->id]);
        Storage::disk('public')->assertMissing($family->getRawOriginal('background'));
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
