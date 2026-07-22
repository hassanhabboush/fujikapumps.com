<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    private function user(int $active = 1): User
    {
        return User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.test',
            'password' => bcrypt('secret'),
            'active'   => $active,
            'role'     => 1,
        ]);
    }

    public function test_a_successful_login_lands_on_the_category_screen(): void
    {
        $this->user();

        $this->post('/checklogin', [
            'email'    => 'admin@example.test',
            'password' => 'secret',
        ])->assertRedirect(route('admin.categories.index'));

        $this->assertAuthenticated();
    }

    public function test_an_inactive_user_is_sent_back_with_an_error(): void
    {
        $this->user(active: 0);

        $this->from('/login')
            ->post('/checklogin', [
                'email'    => 'admin@example.test',
                'password' => 'secret',
            ])
            ->assertRedirect('/login')
            ->assertSessionHas('error', 'Account Inactive');
    }
}
