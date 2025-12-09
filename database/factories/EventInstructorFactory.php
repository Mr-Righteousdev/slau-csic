<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventInstructor>
 */
class EventInstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = $this->faker->randomElement(['primary', 'co-instructor', 'guest_speaker', 'assistant']);

        return [
            'event_id' => \App\Models\Event::factory(),
            'user_id' => \App\Models\User::factory(),
            'role' => $role,
            'guest_details' => $role === 'guest_speaker' ? $this->faker->sentence() : null,
        ];
    }
}
