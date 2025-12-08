<?php

namespace Database\Factories;

use App\Models\BudgetCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BudgetAllocation>
 */
class BudgetAllocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'budget_category_id' => BudgetCategory::factory(),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'semester' => fake()->randomElement(['Fall', 'Spring']),
            'academic_year' => fake()->randomElement(['2024-2025', '2025-2026']),
            'notes' => fake()->sentence(),
        ];
    }
}
