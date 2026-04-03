<?php

namespace Database\Factories;

use App\Models\ClubResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClubResourceProgress>
 */
class ClubResourceProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'club_resource_id' => ClubResource::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['not_started', 'in_progress', 'completed']),
            'progress_percentage' => fake()->numberBetween(0, 100),
            'completed_units' => fake()->numberBetween(0, 10),
            'score' => fake()->numberBetween(0, 500),
            'ranking' => fake()->optional()->randomElement(['Top 5', 'Top 10', 'Quarter Finalist']),
            'notes' => fake()->optional()->sentence(),
            'last_activity_at' => now()->subDays(fake()->numberBetween(0, 14)),
        ];
    }
}
