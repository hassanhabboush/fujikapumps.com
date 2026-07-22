<?php

namespace Tests\Feature;

use App\Models\About;
use App\Models\Gallery;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\Concerns\ActsAsAdmin;
use Tests\TestCase;

class AboutControllerTest extends TestCase
{
    use ActsAsAdmin, RefreshDatabase;

    /** @var array<int, string> */
    private array $dirs = ['gallery', 'team', 'accessoriesuploads'];

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
        // Only sweep what this test added — the directories hold real assets.
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

    private function about(): About
    {
        return About::create(['title1' => 'Original']);
    }

    public function test_guests_cannot_reach_the_about_screens(): void
    {
        $this->get('/about_page')->assertRedirect('/login');
        $this->get('/about_page/gallery')->assertRedirect('/login');
        $this->get('/about_page/team')->assertRedirect('/login');
    }

    public function test_show_returns_the_about_row(): void
    {
        $this->about();

        $this->actingAs($this->admin())
            ->getJson('/about_page/data')
            ->assertOk()
            ->assertJsonPath('data.title1', 'Original');
    }

    public function test_update_saves_the_details(): void
    {
        $about = $this->about();

        $this->actingAs($this->admin())
            ->put('/about_page', [
                'link'   => 'https://youtu.be/abc',
                'title1' => 'Updated',
                'desc1'  => 'Some copy',
            ])
            ->assertRedirect();

        $about->refresh();

        $this->assertSame('Updated', $about->title1);
        $this->assertSame('https://youtu.be/abc', $about->linkyoutube);
    }

    public function test_update_rejects_a_non_image_upload(): void
    {
        $this->about();

        $this->actingAs($this->admin())
            ->put('/about_page', [
                'title1' => 'Updated',
                'image'  => UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf'),
            ])
            ->assertSessionHasErrors('image');
    }

    public function test_gallery_images_can_be_listed_added_and_removed(): void
    {
        $this->actingAs($this->admin())
            ->post('/about_page/gallery', ['background' => UploadedFile::fake()->image('g.jpg')])
            ->assertRedirect();

        $image = Gallery::firstOrFail();
        $stored = public_path('gallery/' . basename($image->getRawOriginal('path')));
        $this->assertFileExists($stored);

        $this->actingAs($this->admin())
            ->getJson('/about_page/gallery/data')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->actingAs($this->admin())
            ->delete('/about_page/gallery/' . $image->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('gallery', ['id' => $image->id]);
        $this->assertFileDoesNotExist($stored);
    }

    public function test_gallery_requires_an_image(): void
    {
        $this->actingAs($this->admin())
            ->post('/about_page/gallery', [])
            ->assertSessionHasErrors('background');

        $this->assertDatabaseCount('gallery', 0);
    }

    public function test_team_images_can_be_added_and_removed(): void
    {
        $this->actingAs($this->admin())
            ->post('/about_page/team', ['background' => UploadedFile::fake()->image('t.jpg')])
            ->assertRedirect();

        $member = Team::firstOrFail();
        $stored = public_path('team/' . basename($member->getRawOriginal('path')));
        $this->assertFileExists($stored);

        $this->actingAs($this->admin())
            ->delete('/about_page/team/' . $member->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('team', ['id' => $member->id]);
        $this->assertFileDoesNotExist($stored);
    }
}
