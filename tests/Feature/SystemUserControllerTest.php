<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\Concerns\ActsAsAdmin;
use Tests\TestCase;

class SystemUserControllerTest extends TestCase
{
    use ActsAsAdmin, RefreshDatabase;

    public function test_guests_cannot_reach_the_system_user_screen(): void
    {
        $this->get('/system_users')->assertRedirect('/login');
    }

    public function test_data_maps_role_and_active_to_labels(): void
    {
        $user = $this->admin();

        $this->actingAs($user)
            ->getJson('/system_users/data')
            ->assertOk()
            ->assertJsonPath('data.0.role', 'Admin')
            ->assertJsonPath('data.0.active', 'active');
    }

    public function test_show_never_exposes_the_password(): void
    {
        $user = $this->admin();

        $response = $this->actingAs($user)
            ->getJson('/system_users/' . $user->id)
            ->assertOk();

        $this->assertArrayNotHasKey('password', $response->json('data.0'));
    }

    public function test_store_creates_an_inactive_user_with_a_hashed_password(): void
    {
        $this->actingAs($this->admin())
            ->post('/system_users', [
                'name'     => 'New Admin',
                'email'    => 'new@example.test',
                'password' => 'sup3rsecret',
                'role'     => 1,
            ])
            ->assertRedirect();

        $user = User::firstWhere('email', 'new@example.test');

        $this->assertNotNull($user);
        $this->assertSame(0, (int) $user->active);
        $this->assertTrue(Hash::check('sup3rsecret', $user->password));
    }

    public function test_store_rejects_a_duplicate_email_and_a_short_password(): void
    {
        $existing = $this->admin();

        $this->actingAs($existing)
            ->post('/system_users', [
                'name'     => 'Dupe',
                'email'    => $existing->email,
                'password' => 'short',
                'role'     => 1,
            ])
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_store_rejects_an_unknown_role(): void
    {
        $this->actingAs($this->admin())
            ->post('/system_users', [
                'name'     => 'New',
                'email'    => 'new@example.test',
                'password' => 'sup3rsecret',
                'role'     => 99,
            ])
            ->assertSessionHasErrors('role');
    }

    /**
     * The old edit() compared the submitted password against a session copy of
     * the hash, which never matched, and the edit form populated that box from
     * a hidden field that serialised as undefined — so saving a user set their
     * password to the literal string "undefined".
     */
    public function test_update_without_a_password_leaves_the_existing_one_intact(): void
    {
        $user = $this->admin();
        $user->update(['password' => Hash::make('originalpass')]);

        $this->actingAs($this->admin())
            ->put('/system_users/' . $user->id, [
                'name'  => 'Renamed',
                'email' => $user->email,
                'role'  => 2,
            ])
            ->assertRedirect();

        $user->refresh();

        $this->assertSame('Renamed', $user->name);
        $this->assertTrue(Hash::check('originalpass', $user->password));
        $this->assertFalse(Hash::check('undefined', $user->password));
    }

    public function test_update_changes_the_password_when_one_is_supplied(): void
    {
        $user = $this->admin();

        $this->actingAs($this->admin())
            ->put('/system_users/' . $user->id, [
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => 'brandnewpass',
                'role'     => 1,
            ])
            ->assertRedirect();

        $this->assertTrue(Hash::check('brandnewpass', $user->refresh()->password));
    }

    public function test_update_allows_keeping_the_same_email(): void
    {
        $user = $this->admin();

        $this->actingAs($this->admin())
            ->put('/system_users/' . $user->id, [
                'name'  => 'Same Email',
                'email' => $user->email,
                'role'  => 1,
            ])
            ->assertSessionHasNoErrors();
    }

    public function test_activate_and_deactivate_require_patch_not_get(): void
    {
        $user = $this->admin();
        $user->update(['active' => 0]);

        // Asserted against the route table rather than by issuing a GET: the
        // public catch-all route /{id}/{type}/{name2} matches any unclaimed
        // three-segment path, so a GET here renders the public site, not a 404.
        $methods = collect(Route::getRoutes())
            ->filter(fn ($route) => $route->uri() === 'system_users/{system_user}/activate')
            ->flatMap(fn ($route) => $route->methods())
            ->all();

        $this->assertContains('PATCH', $methods);
        $this->assertNotContains('GET', $methods);

        $this->actingAs($this->admin())
            ->patch('/system_users/' . $user->id . '/activate')
            ->assertRedirect();

        $this->assertSame(1, (int) $user->refresh()->active);

        $this->actingAs($this->admin())
            ->patch('/system_users/' . $user->id . '/deactivate')
            ->assertRedirect();

        $this->assertSame(0, (int) $user->refresh()->active);
    }

    public function test_destroy_removes_the_user(): void
    {
        $victim = $this->admin();

        $this->actingAs($this->admin())
            ->delete('/system_users/' . $victim->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('users', ['id' => $victim->id]);
    }
}
