<?php

namespace Database\Factories;

use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Presensi>
 */
class PresensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jamMasuk = $this->faker->time('H:i:s');
        // Ensure jam_out is after jam_in if not null
        $jamKeluar = null;
        if ($this->faker->boolean(70)) { // 70% chance of having jam_out
            $jamKeluar = Carbon::createFromTimeString($jamMasuk)
                ->addHours($this->faker->numberBetween(4, 8))
                ->addMinutes($this->faker->numberBetween(0, 59))
                ->format('H:i:s');
        }

        return [
            'karyawan_email' => function () {
                return (Karyawan::inRandomOrder()->first() ?? Karyawan::factory()->create())->email;
            },
            'tgl_presensi' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'jam_in' => $jamMasuk,
            'jam_out' => $jamKeluar,
            'foto_in' => $this->faker->imageUrl(640, 480, 'people', true, 'checkin'),
            'foto_out' => $jamKeluar ? $this->faker->imageUrl(640, 480, 'people', true, 'checkout') : null,
            'lokasi_in' => $this->faker->latitude() . ',' . $this->faker->longitude(),
            'lokasi_out' => $jamKeluar ? ($this->faker->latitude() . ',' . $this->faker->longitude()) : null,
        ];
    }
}
