<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\ActsAsAdmin;
use Tests\TestCase;

/**
 * HasMediaUrls returns media columns as absolute URLs, so a Kendo column template
 * must drop ${background} / ${photo} in verbatim. The nested drill-down screens
 * still prefixed them ("../${background}", "{{ url('${photo}') }}") from back when
 * the API returned a relative path, which produced a broken src and a blank cell.
 */
class GridImageTemplateTest extends TestCase
{
    use ActsAsAdmin, RefreshDatabase;

    public static function screenProvider(): array
    {
        return [
            'subcategories of a category' => ['sub_categories/category/', 'background'],
            'subcategory1 of a subcategory' => ['sub_categories1/parent/', 'background'],
            'families of a subcategory1' => ['families/subcategory/', 'background'],
            'products of a category' => ['products/by-category/', 'photo'],
            'products of a subcategory' => ['products/by-subcategory/', 'photo'],
        ];
    }

    #[DataProvider('screenProvider')]
    public function test_drilldown_grid_uses_the_media_url_unmodified(string $path, string $field): void
    {
        $parent = match ($field) {
            'photo' => Category::factory()->create(),
            default => SubCategory::factory()->create(),
        };

        $response = $this->actingAs($this->admin())
            ->get('/' . $path . $parent->id)
            ->assertOk();

        $response->assertSee("src='\${" . $field . "}'", false);
        $response->assertDontSee("../\${" . $field . "}", false);
        $response->assertDontSee("url('\${" . $field . "}')", false);
    }
}
