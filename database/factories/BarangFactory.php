<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement([
                'PICKED',
                'BOARDING',
                'LOADING',
                'DEPARTURE',
                'FINISHED'
            ]),

            'type' => $this->faker->randomElement(['SJ', 'TITIP']),

            'id_outlet' => $this->faker->optional()->randomElement([
                'HS01',
                'HS02',
                'HS03'
            ]),

            'sjcode' => $this->faker->optional()->bothify('SJ####'),

            'boxqty' => $this->faker->optional()->numberBetween(1, 50),

            'desc' => $this->faker->optional()->sentence()
        ];
    }
}
