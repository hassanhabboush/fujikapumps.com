<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductParameter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\Concerns\ActsAsAdmin;
use Tests\TestCase;

class ParameterControllerTest extends TestCase
{
    use ActsAsAdmin, RefreshDatabase;

    public function test_guests_cannot_reach_the_parameter_screen(): void
    {
        $product = Product::factory()->create();

        $this->get('/products/' . $product->id . '/parameters')->assertRedirect('/login');
    }

    /**
     * Creating and deleting used to be GET routes, so a prefetch or a crawler
     * could write rows.
     */
    public function test_parameters_cannot_be_created_or_deleted_over_get(): void
    {
        $product = Product::factory()->create();
        $parameter = ProductParameter::factory()->create(['product_id' => $product->id]);

        $this->actingAs($this->admin())
            ->get('/products/' . $product->id . '/parameters/data?Model=X')
            ->assertOk();

        $this->assertDatabaseCount('product_parameter', 1);
        $this->assertDatabaseHas('product_parameter', ['id' => $parameter->id]);
    }

    public function test_data_returns_only_that_products_parameters(): void
    {
        $product = Product::factory()->create();
        $other = Product::factory()->create();

        ProductParameter::factory()->create(['product_id' => $product->id, 'Model' => 'Mine']);
        ProductParameter::factory()->create(['product_id' => $other->id, 'Model' => 'Theirs']);

        $this->actingAs($this->admin())
            ->getJson('/products/' . $product->id . '/parameters/data')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.Model', 'Mine');
    }

    public function test_store_creates_a_parameter_against_the_product_in_the_url(): void
    {
        $product = Product::factory()->create();
        $other = Product::factory()->create();

        $this->actingAs($this->admin())
            ->withSession(['product_id' => $other->id])
            ->postJson('/products/' . $product->id . '/parameters', [
                'Model'        => 'M-100',
                'SerialNumber' => 'SN-1',
            ])
            ->assertOk()
            ->assertJsonPath('data.0.Model', 'M-100');

        $this->assertDatabaseHas('product_parameter', [
            'product_id' => $product->id,
            'Model'      => 'M-100',
        ]);
        $this->assertDatabaseMissing('product_parameter', ['product_id' => $other->id]);
    }

    public function test_store_requires_a_model(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin())
            ->postJson('/products/' . $product->id . '/parameters', ['SerialNumber' => 'SN-1'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('Model');

        $this->assertDatabaseCount('product_parameter', 0);
    }

    public function test_update_changes_the_row(): void
    {
        $parameter = ProductParameter::factory()->create(['Model' => 'M-100']);

        $this->actingAs($this->admin())
            ->putJson('/parameters/' . $parameter->id, ['Model' => 'M-200'])
            ->assertOk();

        $this->assertSame('M-200', $parameter->refresh()->Model);
    }

    public function test_destroy_removes_the_row_and_invalidates_the_cache(): void
    {
        $product = Product::factory()->create();
        $parameter = ProductParameter::factory()->create(['product_id' => $product->id]);

        $this->actingAs($this->admin())
            ->getJson('/products/' . $product->id . '/parameters/data')->assertOk();
        $this->assertNotNull(Cache::get('parameter_' . $product->id));

        $this->actingAs($this->admin())
            ->deleteJson('/parameters/' . $parameter->id)
            ->assertOk();

        $this->assertDatabaseMissing('product_parameter', ['id' => $parameter->id]);
        $this->assertNull(Cache::get('parameter_' . $product->id));
    }
}
