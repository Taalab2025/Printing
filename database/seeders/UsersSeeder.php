<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@printingmarketplace.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'language_pref' => 'en',
            'email_verified_at' => now(),
        ]);

        // Attach admin role
        $admin->roles()->attach(1); // Admin role ID

        // Create vendor users
        $vendor1 = User::create([
            'name' => 'Vendor One',
            'email' => 'vendor1@printingmarketplace.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'company' => 'Printing Solutions Ltd',
            'phone' => '+201234567890',
            'language_pref' => 'en',
            'email_verified_at' => now(),
        ]);

        $vendor2 = User::create([
            'name' => 'Vendor Two',
            'email' => 'vendor2@printingmarketplace.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'company' => 'Cairo Print Masters',
            'phone' => '+201234567891',
            'language_pref' => 'ar',
            'email_verified_at' => now(),
        ]);

        // Attach vendor role
        $vendor1->roles()->attach(2); // Vendor role ID
        $vendor2->roles()->attach(2); // Vendor role ID

        // Create customer users
        $customer1 = User::create([
            'name' => 'Customer One',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'language_pref' => 'en',
            'email_verified_at' => now(),
        ]);

        $customer2 = User::create([
            'name' => 'Customer Two',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'language_pref' => 'ar',
            'email_verified_at' => now(),
        ]);

        // Attach customer role
        $customer1->roles()->attach(3); // Customer role ID
        $customer2->roles()->attach(3); // Customer role ID
    }
}
