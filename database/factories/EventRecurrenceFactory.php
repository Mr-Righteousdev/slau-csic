<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventRecurrence>
 */
class EventRecurrenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => \App\Models\Event::factory(),
            'pattern' => $this->faker->randomElement(['weekly', 'biweekly', 'monthly']),
            'interval' => 1,
            'ends_at' => $this->faker->dateTimeBetween('+2 months', '+6 months'),
        ];
    }
}
