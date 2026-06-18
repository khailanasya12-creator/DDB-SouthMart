<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Admin User
        User::updateOrCreate(
            ['email' => 'admin@southmart.id'],
            [
                'name' => 'Admin Pusat',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Default Cashier User
        User::updateOrCreate(
            ['email' => 'tebet@southmart.id'],
            [
                'name' => 'Kasir Tebet',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ]
        );
    }
}
