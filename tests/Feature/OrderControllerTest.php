<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\ActsAsAdmin;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use ActsAsAdmin, RefreshDatabase;

    private function order(int $userId = 1, string $status = 'new'): int
    {
        return DB::table('orders')->insertGetId([
            'user_id'        => $userId,
            'source'         => 'web',
            'status'         => $status,
            'order_date'     => now()->toDateString(),
            'tatal'          => 10.00,
            'payment_method' => 'cash',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    public function test_guests_cannot_read_orders(): void
    {
        $this->get('/orders/data')->assertRedirect('/login');
    }

    public function test_data_returns_all_orders(): void
    {
        $this->order();
        $this->order();

        $this->actingAs($this->admin())
            ->getJson('/orders/data')
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function test_show_returns_a_single_order(): void
    {
        $id = $this->order(status: 'pending');

        $this->actingAs($this->admin())
            ->getJson('/orders/' . $id)
            ->assertOk()
            ->assertJsonPath('status', 'pending');
    }

    public function test_by_user_returns_only_that_users_orders(): void
    {
        $this->order(userId: 7);
        $this->order(userId: 8);

        $this->actingAs($this->admin())
            ->getJson('/users/7/orders')
            ->assertOk()
            ->assertJsonCount(1);
    }

    public function test_items_returns_the_order_items(): void
    {
        $id = $this->order();

        DB::table('order_items')->insert([
            'order_id'   => $id,
            'product_id' => 1,
            'quantity'   => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($this->admin())
            ->getJson('/orders/' . $id . '/items')
            ->assertOk()
            ->assertJsonCount(1);
    }

    public function test_status_can_be_updated_over_patch(): void
    {
        $id = $this->order(status: 'new');

        $this->actingAs($this->admin())
            ->patchJson('/orders/' . $id . '/status', ['status' => 'shipped'])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSame('shipped', DB::table('orders')->where('id', $id)->value('status'));
    }

    public function test_status_is_required(): void
    {
        $id = $this->order(status: 'new');

        $this->actingAs($this->admin())
            ->patchJson('/orders/' . $id . '/status', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('status');

        $this->assertSame('new', DB::table('orders')->where('id', $id)->value('status'));
    }
}
