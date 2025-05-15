<?php

namespace Database\Factories;

use App\Models\Karyawan;
use App\Models\PengajuanIzin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengajuanIzin>
 */
class PengajuanIzinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => Karyawan::inRandomOrder()->first()->nik ?? Karyawan::factory(),
            'tgl_izin' => $this->faker->dateTimeBetween('-2 months', '+1 month')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['i', 's']), // i for izin, s for sakit
            'keterangan' => $this->faker->sentence(),
            'status_approved' => $this->faker->randomElement([0, 1, 2]), // 0: pending, 1: approved, 2: rejected
        ];
    }
}
