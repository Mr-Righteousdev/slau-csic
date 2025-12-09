<?php

namespace Database\Factories;

use App\Models\Fine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinePayment>
 */
class FinePaymentFactory extends Factory
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
            'amount' => fake()->randomFloat(2, 1, 50),
            'payment_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'payment_method' => fake()->randomElement(['cash', 'check', 'card', 'transfer', 'other']),
            'receipt_number' => fake()->optional(0.7)->numerify('REC-####-####'),
            'recorded_by' => User::factory(),
            'notes' => fake()->optional(0.5)->sentence(),
        ];
    }

    public function cash(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cash',
        ]);
    }

    public function check(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'check',
        ]);
    }

    public function card(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'card',
        ]);
    }

    public function transfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'transfer',
        ]);
    }
}
