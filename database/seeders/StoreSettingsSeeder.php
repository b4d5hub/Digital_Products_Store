<?php

namespace Database\Seeders;

use App\Models\StoreSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'store_name' => 'My Store',
            'currency' => 'USD',
            'logo' => '',
            'primary_color' => '#0F172A',
            'secondary_color' => '#1E293B',
            'text_dark' => '#0F172A',
            'text_light' => '#F8FAFC',
        ] as $name => $value) {
            StoreSetting::firstOrCreate(['name' => $name], ['value' => $value]);
        }
    }
}
