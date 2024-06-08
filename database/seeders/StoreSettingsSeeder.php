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
        StoreSetting::updateOrCreate(['name' => 'store_name'], ['value' => 'My Store']);
        StoreSetting::updateOrCreate(['name' => 'currency'], ['value' => 'USD']);
        StoreSetting::updateOrCreate(['name' => 'logo'], ['value' => null]);
        StoreSetting::updateOrCreate(['name' => 'primary_color'], ['value' => '#090b12']);
        StoreSetting::updateOrCreate(['name' => 'secondary_color'], ['value' => '#1c1e25']);
        StoreSetting::updateOrCreate(['name' => 'text_dark'], ['value' => '#000']);
        StoreSetting::updateOrCreate(['name' => 'text_light'], ['value' => '#fff']);
    }
}
