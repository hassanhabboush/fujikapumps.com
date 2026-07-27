<?php

namespace Tests\Feature;

use App\Models\Family;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\Concerns\CleansUploadDirectory;
use Tests\TestCase;

class NormalizeFamilyMediaTest extends TestCase
{
    use CleansUploadDirectory, RefreshDatabase;

    private const BACKGROUND_DIR = 'categorybackground';

    /** @var array<int, string> */
    private array $diskFiles = [];

    protected function uploadDirectory(): string
    {
        return self::BACKGROUND_DIR;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->snapshotUploadDirectory();
        File::ensureDirectoryExists(storage_path('app/public/' . self::BACKGROUND_DIR));
    }

    protected function tearDown(): void
    {
        foreach ($this->diskFiles as $path) {
            File::delete($path);
        }

        $this->cleanUploadDirectory();
        parent::tearDown();
    }

    public function test_it_moves_public_disk_backgrounds_into_public_and_rewrites_the_path(): void
    {
        [$family, $stored] = $this->familyOnThePublicDisk();

        $this->artisan('app:normalize-family-media')->assertSuccessful();

        $normalized = $family->refresh()->getRawOriginal('background');

        $this->assertSame('public/' . $stored, $normalized);
        $this->assertFileExists(public_path($stored));
        $this->assertFileDoesNotExist(storage_path('app/public/' . $stored));
    }

    public function test_it_moves_the_webp_sibling_too(): void
    {
        [, $stored] = $this->familyOnThePublicDisk();

        $webp = preg_replace('/\.[^.]+$/', '.webp', $stored);
        $this->putOnDisk($webp, 'webp');

        $this->artisan('app:normalize-family-media')->assertSuccessful();

        $this->assertFileExists(public_path($webp));
        $this->assertFileDoesNotExist(storage_path('app/public/' . $webp));
    }

    public function test_dry_run_changes_nothing(): void
    {
        [$family, $stored] = $this->familyOnThePublicDisk();

        $this->artisan('app:normalize-family-media', ['--dry-run' => true])->assertSuccessful();

        $this->assertSame($stored, $family->refresh()->getRawOriginal('background'));
        $this->assertFileExists(storage_path('app/public/' . $stored));
        $this->assertFileDoesNotExist(public_path($stored));
    }

    public function test_it_leaves_rows_already_under_public_alone(): void
    {
        $family = Family::factory()->create([
            'background' => 'public/' . self::BACKGROUND_DIR . '/untouched.jpg',
        ]);

        $this->artisan('app:normalize-family-media')->assertSuccessful();

        $this->assertSame(
            'public/' . self::BACKGROUND_DIR . '/untouched.jpg',
            $family->refresh()->getRawOriginal('background')
        );
    }

    /**
     * public/categorybackground is shared with Category, so a name already in
     * use must not be overwritten by the move.
     */
    public function test_it_does_not_overwrite_an_existing_file_of_the_same_name(): void
    {
        [$family, $stored] = $this->familyOnThePublicDisk();

        File::put(public_path($stored), 'belongs-to-a-category');

        $this->artisan('app:normalize-family-media')->assertSuccessful();

        $normalized = $family->refresh()->getRawOriginal('background');

        $this->assertNotSame('public/' . $stored, $normalized);
        $this->assertSame('belongs-to-a-category', File::get(public_path($stored)));
        $this->assertFileExists(public_path(self::BACKGROUND_DIR . '/' . basename($normalized)));
    }

    public function test_it_leaves_a_row_whose_file_is_missing_untouched(): void
    {
        $family = Family::factory()->create([
            'background' => self::BACKGROUND_DIR . '/' . Str::uuid() . '.jpg',
        ]);
        $stored = $family->getRawOriginal('background');

        $this->artisan('app:normalize-family-media')->assertSuccessful();

        $this->assertSame($stored, $family->refresh()->getRawOriginal('background'));
    }

    /**
     * A family stored the old way: file under storage/app/public, path with no
     * public/ prefix.
     *
     * @return array{0: Family, 1: string}
     */
    private function familyOnThePublicDisk(): array
    {
        $stored = self::BACKGROUND_DIR . '/' . Str::uuid() . '.jpg';
        $this->putOnDisk($stored, 'jpg');

        return [Family::factory()->create(['background' => $stored]), $stored];
    }

    private function putOnDisk(string $relativePath, string $contents): void
    {
        $path = storage_path('app/public/' . $relativePath);
        File::put($path, $contents);
        $this->diskFiles[] = $path;
    }
}
