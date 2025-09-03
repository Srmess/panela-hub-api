<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::query()->paginate();

        return RecipeResource::collection($recipes);
    }

    public function show(Recipe $recipe)
    {
        return RecipeResource::make($recipe->load(['author', 'ingredients', 'instructions']));
    }

    public function store(StoreRecipeRequest $request)
    {
        $recipePayload = $request->getValidatedPayload();

        $recipe = DB::transaction(function () use ($recipePayload) {
            $recipe = Recipe::query()->create(
                $recipePayload['recipe']
            );
            $recipe->instructions()->createMany($recipePayload['instructions']);
            $recipe->ingredients()->createMany($recipePayload['ingredients']);

            return $recipe;
        });

        return RecipeResource::make($recipe);
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $recipePayload = $request->getValidatedPayload();

        $recipe = DB::transaction(function () use ($recipe, $recipePayload) {
            $recipe->update(
                $recipePayload['recipe']
            );

            $recipe->instructions()->delete();
            $recipe->ingredients()->delete();

            $recipe->instructions()->createMany($recipePayload['instructions']);
            $recipe->ingredients()->createMany($recipePayload['ingredients']);

            return $recipe;
        });

        return RecipeResource::make($recipe);
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return response()->noContent();
    }
}
