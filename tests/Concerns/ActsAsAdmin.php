<?php

namespace Tests\Concerns;

use App\Models\User;

trait ActsAsAdmin
{
    /**
     * The whole admin panel sits behind `auth`, and the views additionally
     * gate on role 1/2, so tests need a real active admin row.
     */
    protected function admin(int $role = 1): User
    {
        return User::create([
            'name'     => 'Admin',
            'email'    => 'admin' . fake()->unique()->numberBetween(1, 999999) . '@example.test',
            'password' => bcrypt('secret'),
            'active'   => 1,
            'role'     => $role,
        ]);
    }
}
