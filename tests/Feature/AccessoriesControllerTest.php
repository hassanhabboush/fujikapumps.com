<?php

namespace Tests\Feature;

use App\Models\Accessory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\Concerns\ActsAsAdmin;
use Tests\Concerns\CleansUploadDirectory;
use Tests\TestCase;

class AccessoriesControllerTest extends TestCase
{
    use ActsAsAdmin, CleansUploadDirectory, RefreshDatabase;

    protected function uploadDirectory(): string
    {
        return 'accessoriesuploads';
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

    public function test_guests_cannot_reach_the_accessories_screen(): void
    {
        $this->get('/accessories')->assertRedirect('/login');
    }

    public function test_data_returns_only_the_requested_page_with_a_total(): void
    {
        Accessory::factory()->count(12)->create();

        $this->actingAs($this->admin())
            ->getJson('/accessories/data?page=1&pageSize=8')
            ->assertOk()
            ->assertJsonCount(8, 'data')
            ->assertJsonPath('total', 12);

        $this->actingAs($this->admin())
            ->getJson('/accessories/data?page=2&pageSize=8')
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonPath('total', 12);
    }

    public function test_data_returns_accessories_with_resolved_photo_urls(): void
    {
        $accessory = Accessory::factory()->create([
            'name'  => 'Remote',
            'photo' => 'public/accessoriesuploads/sample.jpg',
        ]);

        $this->actingAs($this->admin())
            ->getJson('/accessories/data')
            ->assertOk()
            ->assertJsonPath('data.0.id', $accessory->id)
            ->assertJsonPath('data.0.name', 'Remote')
            ->assertJsonPath('data.0.photo', asset('accessoriesuploads/sample.jpg'));
    }

    public function test_data_is_cached_and_invalidated_by_a_write(): void
    {
        Accessory::factory()->create();

        $this->actingAs($this->admin())->getJson('/accessories/data')->assertOk();
        $this->assertNotNull(Cache::get('accessories'));

        Accessory::factory()->create();
        $this->assertNull(Cache::get('accessories'));
    }

    public function test_store_creates_an_accessory_and_persists_the_upload(): void
    {
        $this->actingAs($this->admin())
            ->post('/accessories', [
                'name'  => 'Remote',
                'link'  => 'https://example.test',
                'image' => UploadedFile::fake()->image('remote.jpg'),
            ])
            ->assertRedirect();

        $accessory = Accessory::firstWhere('name', 'Remote');

        $this->assertNotNull($accessory);
        $stored = $accessory->getRawOriginal('photo');
        $this->assertFileExists(public_path('accessoriesuploads/' . basename($stored)));
    }

    public function test_store_rejects_a_missing_name(): void
    {
        $this->actingAs($this->admin())
            ->post('/accessories', ['image' => UploadedFile::fake()->image('a.jpg')])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('accessories', 0);
    }

    public function test_update_changes_the_name(): void
    {
        $accessory = Accessory::factory()->create(['name' => 'Remote']);

        $this->actingAs($this->admin())
            ->put('/accessories/' . $accessory->id, ['name' => 'Wall Remote'])
            ->assertRedirect();

        $this->assertSame('Wall Remote', $accessory->refresh()->name);
    }

    public function test_destroy_removes_the_row_and_its_file(): void
    {
        $file = public_path('accessoriesuploads/doomed.jpg');
        File::put($file, 'jpg');

        $accessory = Accessory::factory()->create([
            'photo' => 'public/accessoriesuploads/doomed.jpg',
        ]);

        $this->actingAs($this->admin())
            ->delete('/accessories/' . $accessory->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('accessories', ['id' => $accessory->id]);
        $this->assertFileDoesNotExist($file);
    }
}
