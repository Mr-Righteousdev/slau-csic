<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClubResource>
 */
class ClubResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(10, 999),
            'category' => fake()->randomElement(['competition', 'voting', 'ctf', 'class']),
            'platform' => fake()->randomElement(['Internal', 'Hack The Box', 'PicoCTF', 'Google Meet']),
            'difficulty' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'status' => fake()->randomElement(['open', 'scheduled', 'active']),
            'location' => fake()->randomElement(['SLAU Lab', 'Online', 'Hybrid']),
            'cta_label' => 'Open Resource',
            'external_url' => fake()->url(),
            'summary' => fake()->paragraph(),
            'details' => fake()->paragraph(),
            'target_total' => fake()->numberBetween(1, 20),
            'points' => fake()->numberBetween(10, 250),
            'starts_at' => now()->addDays(fake()->numberBetween(1, 20)),
            'ends_at' => now()->addDays(fake()->numberBetween(21, 40)),
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}
