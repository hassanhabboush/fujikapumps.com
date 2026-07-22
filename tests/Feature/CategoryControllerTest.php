<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private const BACKGROUND_DIR = 'categorybackground';

    /**
     * Uploads are moved into the real public directory rather than a fake disk,
     * so anything a test creates there has to be swept up afterwards.
     *
     * @var array<int, string>
     */
    private array $filesBefore = [];

    /**
     * Guards tearDown: if setUp aborted before the snapshot was taken, the
     * "everything new" diff would cover the entire directory and wipe the real
     * catalog images. Cleanup only runs once this is true.
     */
    private bool $snapshotTaken = false;

    protected function setUp(): void
    {
        parent::setUp();

        File::ensureDirectoryExists(public_path(self::BACKGROUND_DIR));
        $this->filesBefore = $this->backgroundFiles();
        $this->snapshotTaken = true;
    }

    protected function tearDown(): void
    {
        if ($this->snapshotTaken) {
            foreach (array_diff($this->backgroundFiles(), $this->filesBefore) as $path) {
                File::delete($path);
            }
        }

        parent::tearDown();
    }

    /** @return array<int, string> */
    private function backgroundFiles(): array
    {
        return array_map(
            fn ($file) => $file->getPathname(),
            File::files(public_path(self::BACKGROUND_DIR))
        );
    }

    private function admin(): User
    {
        return User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.test',
            'password' => bcrypt('secret'),
            'active'   => 1,
            'role'     => 1,
        ]);
    }

    public function test_guests_cannot_reach_the_category_screen(): void
    {
        $this->get('/categories')->assertRedirect('/login');
    }

    public function test_guests_cannot_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $this->delete('/categories/' . $category->id)->assertRedirect('/login');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_data_returns_categories_with_resolved_background_urls(): void
    {
        $category = Category::factory()->create([
            'english_name' => 'Fans',
            'background'   => 'public/' . self::BACKGROUND_DIR . '/sample.jpg',
        ]);

        $this->actingAs($this->admin())
            ->getJson('/categories/data')
            ->assertOk()
            ->assertJsonPath('data.0.id', $category->id)
            ->assertJsonPath('data.0.english_name', 'Fans')
            ->assertJsonPath('data.0.background', asset(self::BACKGROUND_DIR . '/sample.jpg'));
    }

    public function test_data_is_cached_and_invalidated_by_a_write(): void
    {
        Category::factory()->create(['english_name' => 'Fans']);

        $this->actingAs($this->admin())->getJson('/categories/data')->assertOk();
        $this->assertNotNull(Cache::get('categories'));

        // Category uses InvalidatesCache, which forgets 'categories' on save.
        Category::factory()->create(['english_name' => 'Heaters']);
        $this->assertNull(Cache::get('categories'));

        $this->actingAs($this->admin())
            ->getJson('/categories/data')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_show_returns_a_single_category(): void
    {
        $category = Category::factory()->create(['english_name' => 'Fans']);

        $this->actingAs($this->admin())
            ->getJson('/categories/' . $category->id)
            ->assertOk()
            ->assertJsonPath('data.0.english_name', 'Fans');
    }

    public function test_store_creates_a_category_and_persists_the_upload(): void
    {
        $this->actingAs($this->admin())
            ->post('/categories', [
                'english_name' => 'Fans',
                'background'   => UploadedFile::fake()->image('fans.jpg'),
            ])
            ->assertRedirect();

        $category = Category::firstWhere('english_name', 'Fans');

        $this->assertNotNull($category);
        $stored = $category->getRawOriginal('background');
        $this->assertStringStartsWith('public/' . self::BACKGROUND_DIR . '/', $stored);
        $this->assertFileExists(public_path(self::BACKGROUND_DIR . '/' . basename($stored)));
    }

    public function test_store_rejects_a_missing_name(): void
    {
        $this->actingAs($this->admin())
            ->post('/categories', [
                'background' => UploadedFile::fake()->image('fans.jpg'),
            ])
            ->assertSessionHasErrors('english_name');

        $this->assertDatabaseCount('categories', 0);
    }

    public function test_store_rejects_a_non_image_background(): void
    {
        $this->actingAs($this->admin())
            ->post('/categories', [
                'english_name' => 'Fans',
                'background'   => UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf'),
            ])
            ->assertSessionHasErrors('background');

        $this->assertDatabaseCount('categories', 0);
    }

    public function test_update_renames_a_category_without_touching_the_background(): void
    {
        $category = Category::factory()->create([
            'english_name' => 'Fans',
            'background'   => 'public/' . self::BACKGROUND_DIR . '/keep.jpg',
        ]);

        $this->actingAs($this->admin())
            ->put('/categories/' . $category->id, ['english_name' => 'Ceiling Fans'])
            ->assertRedirect();

        $category->refresh();

        $this->assertSame('Ceiling Fans', $category->english_name);
        $this->assertSame(
            'public/' . self::BACKGROUND_DIR . '/keep.jpg',
            $category->getRawOriginal('background')
        );
    }

    public function test_update_replaces_the_background_and_removes_the_old_file(): void
    {
        $oldFile = public_path(self::BACKGROUND_DIR . '/old.jpg');
        File::put($oldFile, 'old');

        $category = Category::factory()->create([
            'background' => 'public/' . self::BACKGROUND_DIR . '/old.jpg',
        ]);

        $this->actingAs($this->admin())
            ->put('/categories/' . $category->id, [
                'english_name' => 'Fans',
                'background'   => UploadedFile::fake()->image('new.jpg'),
            ])
            ->assertRedirect();

        $category->refresh();
        $stored = $category->getRawOriginal('background');

        $this->assertNotSame('public/' . self::BACKGROUND_DIR . '/old.jpg', $stored);
        $this->assertFileExists(public_path(self::BACKGROUND_DIR . '/' . basename($stored)));
        $this->assertFileDoesNotExist($oldFile);
    }

    public function test_update_rejects_a_missing_name(): void
    {
        $category = Category::factory()->create(['english_name' => 'Fans']);

        $this->actingAs($this->admin())
            ->put('/categories/' . $category->id, ['english_name' => ''])
            ->assertSessionHasErrors('english_name');

        $this->assertSame('Fans', $category->refresh()->english_name);
    }

    public function test_destroy_removes_the_row_the_file_and_its_webp_sibling(): void
    {
        $jpg  = public_path(self::BACKGROUND_DIR . '/doomed.jpg');
        $webp = public_path(self::BACKGROUND_DIR . '/doomed.webp');
        File::put($jpg, 'jpg');
        File::put($webp, 'webp');

        $category = Category::factory()->create([
            'background' => 'public/' . self::BACKGROUND_DIR . '/doomed.jpg',
        ]);

        $this->actingAs($this->admin())
            ->delete('/categories/' . $category->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertFileDoesNotExist($jpg);
        $this->assertFileDoesNotExist($webp);
    }

    public function test_destroy_404s_for_an_unknown_category(): void
    {
        $this->actingAs($this->admin())
            ->delete('/categories/999999')
            ->assertNotFound();
    }
}
