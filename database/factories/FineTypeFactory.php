<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FineType>
 */
class FineTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fineTypes = [
            'Missed Meeting' => [
                'default_amount' => 5.00,
                'description' => 'Fine for missing scheduled club meetings',
                'auto_apply_trigger' => 'missed_meetings',
                'auto_apply_threshold' => 3,
            ],
            'Event No-Show' => [
                'default_amount' => 3.00,
                'description' => 'Fine for not showing up to registered events',
                'auto_apply_trigger' => 'event_no_show',
                'auto_apply_threshold' => 1,
            ],
            'Late Project Submission' => [
                'default_amount' => 2.00,
                'description' => 'Fine for submitting projects after deadline',
                'auto_apply_trigger' => 'late_submission',
                'auto_apply_threshold' => 1,
            ],
            'Lab Violation' => [
                'default_amount' => 20.00,
                'description' => 'Fine for destructive behavior in lab',
                'auto_apply_trigger' => null,
                'auto_apply_threshold' => null,
            ],
            'Equipment Damage' => [
                'default_amount' => 50.00,
                'description' => 'Fine for damaging club equipment',
                'auto_apply_trigger' => null,
                'auto_apply_threshold' => null,
            ],
        ];

        $type = fake()->randomElement(array_keys($fineTypes));
        $data = $fineTypes[$type];

        return [
            'name' => $type,
            'default_amount' => $data['default_amount'],
            'description' => $data['description'],
            'auto_apply_trigger' => $data['auto_apply_trigger'],
            'auto_apply_threshold' => $data['auto_apply_threshold'],
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function manual(): static
    {
        return $this->state(fn (array $attributes) => [
            'auto_apply_trigger' => null,
            'auto_apply_threshold' => null,
        ]);
    }
}
