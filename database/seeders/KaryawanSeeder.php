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
        Karyawan::factory(10)->create(); // Create 10 sample karyawan

        // Create a specific karyawan for testing if needed
        Karyawan::factory()->create([
            'nik' => '1234567890',
            'nama_lengkap' => 'Test Karyawan',
            'jabatan' => 'Tester',
            'password' => Hash::make('password'), // Or use Hash::make()
        ]);
    }
}
