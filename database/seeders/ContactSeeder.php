<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Contact::create([
            'address' => 'Riyadh, Saudi Arabia',
            'phone1' => '+966 50 123 4567',
            'phon2' => '+966 55 987 6543',
            'email' => 'info@fujika.local',
            'facebook' => 'https://facebook.com/fujika',
            'twitter' => 'https://twitter.com/fujika',
            'instagram' => 'https://instagram.com/fujika',
            'whatsapp' => 'https://wa.me/966501234567',
            'linkedin' => 'https://linkedin.com/company/fujika',
            'website' => 'https://fujika.local',
        ]);
    }
}
