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
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('super@12'),
            'role' => 'superadmin',
        ]);

        // Create Admin (will be linked to a company later)
        User::create([
            'username' => 'admin',
            'full_name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@12'),
            'role' => 'admin',
            // 'company_id' will be set later when the company is created
        ]);

        // Create Employees
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'username' => 'employee' . $i,
                'full_name' => 'Employee ' . $i,
                'email' => 'employee' . $i . '@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'employee',
                // 'company_id' will be set later
            ]);
        }
    }
}