<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventResource>
 */
class EventResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['slide', 'code', 'document', 'video', 'link', 'other']);

        return [
            'event_id' => \App\Models\Event::factory(),
            'title' => $this->faker->sentence(2),
            'file_path' => $type === 'link' ? null : 'resources/'.$this->faker->uuid().'.pdf',
            'url' => $type === 'link' ? $this->faker->url() : null,
            'type' => $type,
        ];
    }
}
