<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ebarangayofficial@gmail.com'],
            [
                'name' => 'Barangay Admin',
                'password' => 'password',
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@ebarangay.test'],
            [
                'name' => 'Barangay Staff',
                'password' => 'password',
                'role' => 'staff',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'resident@ebarangay.test'],
            [
                'name' => 'Sample Resident',
                'password' => 'password',
                'role' => 'resident',
                'status' => 'active',
            ]
        );
    }
}
