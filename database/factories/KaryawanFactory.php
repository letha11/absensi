<?php

namespace Database\Factories;

use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Karyawan>
 */
class KaryawanFactory extends Factory
{
    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify('##########'), // 10 digit NIK
            'nama_lengkap' => $this->faker->name(),
            'jabatan' => $this->faker->jobTitle(),
            'password' => static::$password ??= Hash::make('password'),
            // 'no_hp' => $this->faker->phoneNumber(), // Assuming no_hp might be added later
            // 'foto' => null, // Assuming foto might be added later
            // 'kode_dept' => $this->faker->randomElement(['IT', 'HRD', 'MKT']), // Assuming kode_dept
        ];
    }
}
