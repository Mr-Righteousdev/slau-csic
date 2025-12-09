<?php

namespace Database\Factories;

use App\Models\Fine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FineAppeal>
 */
class FineAppealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fine_id' => Fine::factory(),
            'appeal_reason' => fake()->randomElement(['first_offense', 'special_circumstances', 'error', 'other']),
            'explanation' => fake()->paragraph(3),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'submitted_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'reviewed_at' => fn (array $attributes) => $attributes['status'] !== 'pending' ? fake()->dateTimeBetween($attributes['submitted_at'], 'now') : null,
            'reviewed_by' => fn (array $attributes) => $attributes['status'] !== 'pending' ? User::factory() : null,
            'decision_notes' => fn (array $attributes) => $attributes['status'] !== 'pending' ? fake()->sentence() : null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'reviewed_at' => null,
            'reviewed_by' => null,
            'decision_notes' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'reviewed_at' => now(),
            'decision_notes' => 'Appeal approved due to valid reason.',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'reviewed_at' => now(),
            'decision_notes' => 'Appeal rejected. Fine must be paid.',
        ]);
    }

    public function firstOffense(): static
    {
        return $this->state(fn (array $attributes) => [
            'appeal_reason' => 'first_offense',
            'explanation' => 'This is my first offense and I was not aware of the rules.',
        ]);
    }

    public function specialCircumstances(): static
    {
        return $this->state(fn (array $attributes) => [
            'appeal_reason' => 'special_circumstances',
            'explanation' => 'I had special circumstances that prevented me from meeting the requirements.',
        ]);
    }
}
