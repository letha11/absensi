<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DirekturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Akun Direktur',
            'email' => 'direktur@example.com',
            'password' => Hash::make('password'), // Change password in production
            'role' => User::ROLE_DIREKTUR,
            'email_verified_at' => now(),
        ]);
    }
} 