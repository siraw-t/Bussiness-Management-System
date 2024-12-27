<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;

class CompanySeeder extends Seeder
{
    public function run()
    {
        // Create a company and assign the admin
        $company = Company::create([
            'company_name' => 'Re Technology Solutions PLC',
            'country' => 'Ethiopia',
            'city' => 'Addis Ababa',
            'contact_email' => 'retech@gmail.com',
            'contact_phone' => '0919901362',
            'created_by' => 1, // Assuming the superadmin ID is 1
        ]);

        // Assign the admin to the company
        $admin = User::where('username', 'admin')->first();
        $company->company_admin = $admin->user_id; // Set admin user ID
        $company->save(); // Save changes
    }
}