<?php

namespace Database\Factories;

use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

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
        $jamMasukString = $this->faker->time('H:i:s');
        $jamMasukCarbon = Carbon::createFromTimeString($jamMasukString);

        // Ensure jam_out is after jam_in if not null
        $jamKeluar = null;
        if ($this->faker->boolean(70)) { // 70% chance of having jam_out
            $jamKeluar = $jamMasukCarbon->copy()
                ->addHours($this->faker->numberBetween(4, 8))
                ->addMinutes($this->faker->numberBetween(0, 59))
                ->format('H:i:s');
        }

        // Calculate points
        $points = 0;
        $defaultStartTimeString = Config::get('presensi.default_start_time', '07:00:00');
        $defaultStartTime = Carbon::createFromTimeString($defaultStartTimeString);

        if ($jamMasukCarbon->lte($defaultStartTime->copy()->subMinutes(15))) {
            $points = 2;
        } elseif ($jamMasukCarbon->gt($defaultStartTime->copy()->subMinutes(15)) && $jamMasukCarbon->lte($defaultStartTime)) {
            $points = 1;
        } elseif ($jamMasukCarbon->gt($defaultStartTime)) {
            $points = -1;
        }

        return [
            'karyawan_email' => function () {
                return (Karyawan::inRandomOrder()->first() ?? Karyawan::factory()->create())->email;
            },
            'tgl_presensi' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'jam_in' => $jamMasukString,
            'jam_out' => $jamKeluar,
            'foto_in' => $this->faker->imageUrl(640, 480, 'people', true, 'checkin'),
            'foto_out' => $jamKeluar ? $this->faker->imageUrl(640, 480, 'people', true, 'checkout') : null,
            'lokasi_in' => $this->faker->latitude() . ',' . $this->faker->longitude(),
            'lokasi_out' => $jamKeluar ? ($this->faker->latitude() . ',' . $this->faker->longitude()) : null,
            'point' => $points,
        ];
    }
}
