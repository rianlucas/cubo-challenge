<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{

    public function definition(): array
    {
        return [
            "name" => $this->faker->name(),
            "description" => $this->faker->text(),
            "status" => $this->faker->randomElement(['pending', 'completed', 'in progress']),
            "created_at" => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
