<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeView>
 */
class RecipeViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipe_id'  => Recipe::factory(),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
