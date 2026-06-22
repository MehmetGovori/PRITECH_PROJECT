<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-3 months', 'now');

        return [
            'user_id'     => User::factory(),
            'name'        => $this->faker->bs() . ' ' . $this->faker->randomElement(['Platform', 'System', 'Portal', 'Suite', 'Hub']),
            'description' => $this->faker->paragraph(),
            'start_date'  => $start,
            'deadline'    => $this->faker->dateTimeBetween($start, '+6 months'),
        ];
    }
}
