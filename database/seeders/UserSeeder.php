<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
User::create([
    'username' => 'superadmin',
    'full_name' => 'Super Admin',
    'email' => 'sirawbizutadesse21@gmail.com',
    'password' => bcrypt('super@12'),
    'role' => 'superadmin',
]);

// Create Admin
$admin = User::create([
    'username' => 'admin',
    'full_name' => 'Admin User',
    'email' => 'daveyetadulij@gmail.com',
    'password' => bcrypt('admin@12'),
    'role' => 'admin',
    // 'company_id' will be set later
    'profile_image' => null, // Set default or null for now
]);

// Create Employees
for ($i = 1; $i <= 5; $i++) {
    User::create([
        'username' => 'employee' . $i,
        'full_name' => 'Employee ' . $i,
        'email' => 'employee' . $i . '@gmail.com',
        'password' => bcrypt('password'),
        'role' => 'employee',
        'profile_image' => null, // Set default or null for now
    ]);
}
    }
}