<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Predefined users
        $users = [
            [
                'username' => 'superadmin',
                'full_name' => 'Super Admin',
                'email' => 'sirawbizutadesse21@gmail.com',
                'password' => bcrypt('siraw@12'),
                'role' => 'superadmin',
                'email_verified_at' => now(), // Set this to now() for verified or null for unverified
                'profile_image' => null,
            ],
            [
                'username' => 'admin',
                'full_name' => 'Admin User',
                'email' => 'daveyetadulij@gmail.com',
                'password' => bcrypt('siraw@12'),
                'role' => 'admin',
                'email_verified_at' => null, // Set to null if you want them to be unverified
                'profile_image' => null,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Create Employees with random emails without restrictions
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'username' => 'employee' . $i,
                'full_name' => 'Employee ' . $i,
                'email' => $faker->email, // Generate any random email
                'password' => bcrypt('password'),
                'role' => 'employee',
                'email_verified_at' => null, // Set to null for unverified
                'profile_image' => null,
            ]);
        }
    }
}