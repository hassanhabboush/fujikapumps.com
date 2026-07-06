<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if($user = User::firstWhere('email', 'userC@fujikaindustries.com')) {
            $user->update(['password' =>  Hash::make('password1234')]);
        }

        User::create([
            'name'    => 'Geek Admin',
            'email'    => 'geek_admin@fujikaindustries.com',
            'password'   =>  Hash::make('password123'),
            'role' => 1,
        ]);

    }
}