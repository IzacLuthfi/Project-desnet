<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'Admin'
]);

        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Project Manager',
                'email' => 'pm@example.com',
                'role' => 'pm',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Head of Department',
                'email' => 'hod@example.com',
                'role' => 'hod',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@example.com',
                'role' => 'staff',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
