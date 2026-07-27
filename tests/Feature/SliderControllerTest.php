<?php

namespace Tests\Feature;

use App\Models\Slider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\Concerns\ActsAsAdmin;
use Tests\Concerns\CleansUploadDirectory;
use Tests\TestCase;

class SliderControllerTest extends TestCase
{
    use ActsAsAdmin, CleansUploadDirectory, RefreshDatabase;

    protected function uploadDirectory(): string
    {
        return 'slideruploads';
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

    public function test_guests_cannot_reach_the_slider_screen(): void
    {
        $this->get('/sliders')->assertRedirect('/login');
    }

    public function test_data_returns_only_the_requested_page_with_a_total(): void
    {
        Slider::factory()->count(12)->create();

        $this->actingAs($this->admin())
            ->getJson('/sliders/data?page=1&pageSize=8')
            ->assertOk()
            ->assertJsonCount(8, 'data')
            ->assertJsonPath('total', 12);

        $this->actingAs($this->admin())
            ->getJson('/sliders/data?page=2&pageSize=8')
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonPath('total', 12);
    }

    public function test_guests_cannot_delete_a_slide(): void
    {
        $slider = Slider::factory()->create();

        $this->delete('/sliders/' . $slider->id)->assertRedirect('/login');

        $this->assertDatabaseHas('slider', ['id' => $slider->id]);
    }

    public function test_data_returns_slides_with_resolved_image_urls(): void
    {
        $slider = Slider::factory()->create([
            'text1' => 'Welcome',
            'image' => 'public/slideruploads/sample.jpg',
        ]);

        $this->actingAs($this->admin())
            ->getJson('/sliders/data')
            ->assertOk()
            ->assertJsonPath('data.0.id', $slider->id)
            ->assertJsonPath('data.0.text1', 'Welcome')
            ->assertJsonPath('data.0.image', asset('slideruploads/sample.jpg'));
    }

    public function test_store_creates_a_slide_and_persists_the_upload(): void
    {
        $this->actingAs($this->admin())
            ->post('/sliders', [
                'text1'      => 'Welcome',
                'buttontext' => 'Read more',
                'buttonlink' => 'https://example.test',
                'image'      => UploadedFile::fake()->image('slide.jpg'),
            ])
            ->assertRedirect();

        $slider = Slider::firstWhere('text1', 'Welcome');

        $this->assertNotNull($slider);
        $stored = $slider->getRawOriginal('image');
        $this->assertStringStartsWith('public/slideruploads/', $stored);
        $this->assertFileExists(public_path('slideruploads/' . basename($stored)));
    }

    public function test_store_requires_an_image(): void
    {
        $this->actingAs($this->admin())
            ->post('/sliders', ['text1' => 'Welcome'])
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('slider', 0);
    }

    public function test_store_rejects_a_non_image_upload(): void
    {
        $this->actingAs($this->admin())
            ->post('/sliders', [
                'text1' => 'Welcome',
                'image' => UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf'),
            ])
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('slider', 0);
    }

    public function test_update_changes_the_text_without_touching_the_image(): void
    {
        $slider = Slider::factory()->create([
            'text1' => 'Welcome',
            'image' => 'public/slideruploads/keep.jpg',
        ]);

        $this->actingAs($this->admin())
            ->put('/sliders/' . $slider->id, ['text1' => 'Hello'])
            ->assertRedirect();

        $slider->refresh();

        $this->assertSame('Hello', $slider->text1);
        $this->assertSame('public/slideruploads/keep.jpg', $slider->getRawOriginal('image'));
    }

    public function test_update_replaces_the_image_and_removes_the_old_file(): void
    {
        $oldFile = public_path('slideruploads/old.jpg');
        File::put($oldFile, 'old');

        $slider = Slider::factory()->create(['image' => 'public/slideruploads/old.jpg']);

        $this->actingAs($this->admin())
            ->put('/sliders/' . $slider->id, [
                'text1' => 'Hello',
                'image' => UploadedFile::fake()->image('new.jpg'),
            ])
            ->assertRedirect();

        $stored = $slider->refresh()->getRawOriginal('image');

        $this->assertNotSame('public/slideruploads/old.jpg', $stored);
        $this->assertFileExists(public_path('slideruploads/' . basename($stored)));
        $this->assertFileDoesNotExist($oldFile);
    }

    public function test_destroy_removes_the_row_and_its_file(): void
    {
        $file = public_path('slideruploads/doomed.jpg');
        File::put($file, 'jpg');

        $slider = Slider::factory()->create(['image' => 'public/slideruploads/doomed.jpg']);

        $this->actingAs($this->admin())
            ->delete('/sliders/' . $slider->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('slider', ['id' => $slider->id]);
        $this->assertFileDoesNotExist($file);
    }
}
