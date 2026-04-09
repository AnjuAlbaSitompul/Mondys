<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'DC' . $this->faker->unique()->numberBetween(100, 999),
            'name' => 'Gudang ' . $this->faker->city(),
            'address' => $this->faker->address(),
        ];
    }
}
