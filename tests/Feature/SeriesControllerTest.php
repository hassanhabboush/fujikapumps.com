<?php

namespace Tests\Feature;

use App\Models\Family;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\Concerns\ActsAsAdmin;
use Tests\Concerns\CleansUploadDirectory;
use Tests\TestCase;

class SeriesControllerTest extends TestCase
{
    use ActsAsAdmin, CleansUploadDirectory, RefreshDatabase;

    protected function uploadDirectory(): string
    {
        return 'seriesuploads';
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

    public function test_guests_cannot_reach_the_series_screen(): void
    {
        $this->get('/series')->assertRedirect('/login');
    }

    public function test_data_returns_series_with_resolved_photo_urls(): void
    {
        $series = Series::factory()->create([
            'english_name' => 'Alpha',
            'photo'        => 'public/seriesuploads/sample.jpg',
        ]);

        $this->actingAs($this->admin())
            ->getJson('/series/data')
            ->assertOk()
            ->assertJsonPath('data.0.id', $series->id)
            ->assertJsonPath('data.0.english_name', 'Alpha')
            ->assertJsonPath('data.0.photo', asset('seriesuploads/sample.jpg'));
    }

    public function test_data_returns_only_the_requested_page_with_a_total(): void
    {
        Series::factory()->count(12)->create();

        $this->actingAs($this->admin())
            ->getJson('/series/data?page=1&pageSize=8')
            ->assertOk()
            ->assertJsonCount(8, 'data')
            ->assertJsonPath('total', 12);

        $this->actingAs($this->admin())
            ->getJson('/series/data?page=2&pageSize=8')
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonPath('total', 12);
    }

    public function test_store_creates_a_series_and_persists_the_upload(): void
    {
        $family = Family::factory()->create();

        $this->actingAs($this->admin())
            ->post('/series', [
                'english_name' => 'Alpha',
                'family_id'    => $family->id,
                'link'         => 'https://example.test',
                'image'        => UploadedFile::fake()->image('alpha.jpg'),
            ])
            ->assertRedirect();

        $series = Series::firstWhere('english_name', 'Alpha');

        $this->assertNotNull($series);
        $this->assertSame($family->id, $series->family_id);
        $stored = $series->getRawOriginal('photo');
        $this->assertFileExists(public_path('seriesuploads/' . basename($stored)));
    }

    public function test_store_rejects_an_unknown_family(): void
    {
        $this->actingAs($this->admin())
            ->post('/series', [
                'english_name' => 'Alpha',
                'family_id'    => 999999,
                'image'        => UploadedFile::fake()->image('alpha.jpg'),
            ])
            ->assertSessionHasErrors('family_id');

        $this->assertDatabaseCount('series', 0);
    }

    public function test_store_rejects_a_name_longer_than_the_column(): void
    {
        $family = Family::factory()->create();

        $this->actingAs($this->admin())
            ->post('/series', [
                'english_name' => str_repeat('a', 41),
                'family_id'    => $family->id,
                'image'        => UploadedFile::fake()->image('alpha.jpg'),
            ])
            ->assertSessionHasErrors('english_name');

        $this->assertDatabaseCount('series', 0);
    }

    public function test_update_changes_the_name(): void
    {
        $series = Series::factory()->create(['english_name' => 'Alpha']);

        $this->actingAs($this->admin())
            ->put('/series/' . $series->id, [
                'english_name' => 'Beta',
                'family_id'    => $series->family_id,
            ])
            ->assertRedirect();

        $this->assertSame('Beta', $series->refresh()->english_name);
    }

    public function test_destroy_removes_the_row_and_its_file(): void
    {
        $file = public_path('seriesuploads/doomed.jpg');
        File::put($file, 'jpg');

        $series = Series::factory()->create(['photo' => 'public/seriesuploads/doomed.jpg']);

        $this->actingAs($this->admin())
            ->delete('/series/' . $series->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('series', ['id' => $series->id]);
        $this->assertFileDoesNotExist($file);
    }
}
