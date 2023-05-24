<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Graduating>
 */
class GraduatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'category' => fake()->name(),
            'duration' => now(),
            'location' => 'Barcelona', // password
            'partners' => 'Gentis',
            'manager' =>'Naila'
        ];
    }
}
