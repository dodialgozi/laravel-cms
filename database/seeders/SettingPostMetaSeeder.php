<?php

namespace Database\Seeders;

use App\Models\SettingPostMeta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingPostMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingPostMeta::create([
            'setting_meta_code' => 'Berita Hot',
            'setting_meta_type' => 'boolean',
            'setting_meta_value' => 0,
        ]);
        
        SettingPostMeta::create([
            'setting_meta_code' => 'Berita Populer',
            'setting_meta_type' => 'boolean',
            'setting_meta_value' => 0,
        ]);

        SettingPostMeta::create([
            'setting_meta_code' => 'Malay Homeland',
            'setting_meta_type' => 'boolean',
            'setting_meta_value' => 0,
        ]);
    }
}
