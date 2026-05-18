<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'    => 'Geek Admin',
            'email'    => 'geek@admin.com',
            'password'   =>  Hash::make('1234567'),
            'remember_token' =>  str_random(10),
        ]);
    }
}