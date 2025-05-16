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
            'karyawan_email' => function () {
                return (Karyawan::inRandomOrder()->first() ?? Karyawan::factory()->create())->email;
            },
            'tgl_izin' => $this->faker->dateTimeBetween('-2 months', '+1 month')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['i', 's']), // i for izin, s for sakit
            'keterangan' => $this->faker->sentence(),
            'status_approved' => $this->faker->randomElement(['a', 'p', 'd']), // 0: pending, 1: approved, 2: rejected
        ];
    }
}
