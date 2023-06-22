<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name,
            'created_at' => $this->faker->dateTimeBetween('-6 months'),
            'updated_at' => $this->faker->dateTimeBetween('-3 months')

        ];
    }
}
