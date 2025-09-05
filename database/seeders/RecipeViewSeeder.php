<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\RecipeView;
use Illuminate\Database\Seeder;

class RecipeViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::all()->each(function (Recipe $recipe) {
            RecipeView::factory()->count(random_int(0, 200))->create([
                'recipe_id' => $recipe->id,
            ]);
        });
    }
}
