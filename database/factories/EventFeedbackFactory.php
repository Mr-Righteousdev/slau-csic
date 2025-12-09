<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventFeedback>
 */
class EventFeedbackFactory extends Factory
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
            'user_id' => \App\Models\User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'content_quality' => $this->faker->numberBetween(1, 5),
            'instructor_rating' => $this->faker->numberBetween(1, 5),
            'pace_rating' => $this->faker->numberBetween(1, 5),
            'feedback_text' => $this->faker->paragraph(),
            'suggestions' => $this->faker->optional()->paragraph(),
            'is_anonymous' => $this->faker->boolean(20),
        ];
    }
}
