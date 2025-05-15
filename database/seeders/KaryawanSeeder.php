<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a specific karyawan for testing if needed
        Karyawan::factory()->create([
            'nik' => '1234567890',
            'nama_lengkap' => 'Test Karyawan',
            'email' => 'test@example.com',
            'jabatan' => 'Tester',
            'no_hp' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        Karyawan::factory(10)->create(); // Create 10 sample karyawan
    }
}
