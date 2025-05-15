<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\PengajuanIzin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengajuanIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Karyawan::count() == 0) {
            $this->command->info('No Karyawan found, please run KaryawanSeeder first or ensure Karyawan exist.');
            // Optionally, call KaryawanSeeder directly
            // $this->call(KaryawanSeeder::class);
            // return;
        }
        
        PengajuanIzin::factory(20)->create(); // Create 20 sample pengajuan izin records
    }
}
