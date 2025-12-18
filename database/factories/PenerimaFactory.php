<?php

namespace Database\Factories;

use App\Models\Penerima;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penerima>
 */
class PenerimaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Penerima::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nik' => fake()->unique()->numerify('################'),
            'alamat' => fake()->address(),
            'kelurahan' => fake()->streetName(),
            'kecamatan' => fake()->city(),
            'jenis' => fake()->randomElement(['fakir', 'miskin', 'yatim', 'janda', 'duda']),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
        ];
    }
}