<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Plan;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all plan IDs
        $planIds = Plan::pluck('id')->toArray();

        // Create users with random plan_id
        foreach (range(1, 10) as $index) { // Change 10 to the desired number of users
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // You should hash the password
                'plan_id' => $faker->randomElement($planIds), // Random plan_id
            ]);
        }
    }
}
