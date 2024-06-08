<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;


class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'price' => '9.99',
                'monthly_usage' => '10GB',
            ],
            [
                'name' => 'Standard',
                'price' => '19.99',
                'monthly_usage' => '50GB',
            ],
            [
                'name' => 'Premium',
                'price' => '29.99',
                'monthly_usage' => 'Unlimited',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create([
                'name' => $plan['name'],
                'price' => $plan['price'],
                'monthly_usage' => $plan['monthly_usage'],
            ]);
        }
    }
}
