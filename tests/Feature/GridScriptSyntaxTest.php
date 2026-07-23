<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\ActsAsAdmin;
use Tests\TestCase;

/**
 * The Kendo grids are configured from an inline <script>, so a single syntax
 * error anywhere in that block stops the whole thing parsing and the list
 * silently renders empty. The system_users action column built its HTML in a
 * double-quoted JS string but wrote style="cursor:pointer" and
 * onclick='patchTo("...")' with raw double quotes, which closed the string
 * early and blanked the grid.
 */
class GridScriptSyntaxTest extends TestCase
{
    use ActsAsAdmin, RefreshDatabase;

    public static function screenProvider(): array
    {
        return [
            'system users' => ['system_users'],
            'products' => ['products'],
            'about gallery' => ['about_page/gallery'],
            'about team' => ['about_page/team'],
        ];
    }

    #[DataProvider('screenProvider')]
    public function test_inline_grid_script_parses(string $path): void
    {
        $html = $this->actingAs($this->admin())
            ->get('/' . $path)
            ->assertOk()
            ->getContent();

        preg_match_all('/<script(?![^>]*\ssrc=)[^>]*>(.*?)<\/script>/is', $html, $matches);

        $this->assertNotEmpty($matches[1], 'No inline script found on ' . $path);

        foreach ($matches[1] as $i => $script) {
            if (trim($script) === '') {
                continue;
            }

            $file = storage_path('framework/testing/grid-script-' . $i . '.js');
            File::ensureDirectoryExists(dirname($file));
            File::put($file, $script);

            exec('node --check ' . escapeshellarg($file) . ' 2>&1', $output, $status);
            File::delete($file);

            $this->assertSame(
                0,
                $status,
                'Inline script #' . $i . ' on ' . $path . " is not valid JS:\n" . implode("\n", $output)
            );
        }
    }
}
