<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure Karyawan seeder has run or Karyawan exist
        if (Karyawan::count() == 0) {
            $this->command->info('No Karyawan found, please run KaryawanSeeder first or ensure Karyawan exist.');
            // Optionally, call KaryawanSeeder directly
            // $this->call(KaryawanSeeder::class);
            // return;
        }

        // Create 50 presensi records, randomly assigned to existing Karyawan
        Presensi::factory(50)->create();
    }
}
