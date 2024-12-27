<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Call your seeders here
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
        ]);
    }
}