<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Akun Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Change password in production
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);
    }
} 