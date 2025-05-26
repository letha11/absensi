<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class OperasionalDirekturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Akun Operasional Direktur',
            'email' => 'op_direktur@example.com',
            'password' => Hash::make('password'), // Change password in production
            'role' => User::ROLE_OPERASIONAL_DIREKTUR,
            'email_verified_at' => now(),
        ]);
    }
} 