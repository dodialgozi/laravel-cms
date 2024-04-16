<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'user_email' => 'rezkinasrullah22@gmail.com',
            'user_password' => encode('admin'),
            'user_name' => 'Rezki Nasrullah',
            'user_photo' => '',
            'user_nick' => 'Rezki',
            'user_level' => 'administrator',
            'user_active' => 1,
            'user_publish' => 1,
            'user_bio' => 'Galau ga galau yang penting makan',
        ]);
    }
}
