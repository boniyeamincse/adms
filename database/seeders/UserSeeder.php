<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users except the first one if needed
        // User::query()->delete();

        // Create users for different roles
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'John Teacher',
                'email' => 'teacher@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Accountant User',
                'email' => 'accountant@example.com',
                'password' => Hash::make('password'),
                'role' => 'accountant',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Cadet Smith',
                'email' => 'cadet@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Teacher Two',
                'email' => 'teacher2@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Accountant Two',
                'email' => 'accountant2@example.com',
                'password' => Hash::make('password'),
                'role' => 'accountant',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Student One',
                'email' => 'student@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('admin123'),
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test Teacher',
                'email' => 'teacher@test.com',
                'password' => Hash::make('teacher123'),
                'role' => 'teacher',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test Accountant',
                'email' => 'accountant@test.com',
                'password' => Hash::make('accountant123'),
                'role' => 'accountant',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test Cadet',
                'email' => 'cadet@test.com',
                'password' => Hash::make('cadet123'),
                'role' => 'student',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            // Skip if user already exists
            if (!User::where('email', $userData['email'])->exists()) {
                User::create($userData);
                $this->command->info("Created user: {$userData['name']} ({$userData['email']}) - Role: {$userData['role']}");
            } else {
                $this->command->info("User already exists: {$userData['email']}");
            }
        }

        $this->command->info('User seeding completed!');
    }
}
