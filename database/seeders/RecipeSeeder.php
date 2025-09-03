<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Instruction;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function (User $user) {
            $recipes = Recipe::factory()->count(random_int(1, 5))->create([
                'user_id' => $user->id,
            ]);

            $recipes->each(function (Recipe $recipe) {
                Ingredient::factory()->count(random_int(1, 10))->create([
                    'recipe_id' => $recipe->id,
                ]);

                Instruction::factory()->count(random_int(1, 8))->create([
                    'recipe_id' => $recipe->id,
                ]);
            });
        });
    }
}
