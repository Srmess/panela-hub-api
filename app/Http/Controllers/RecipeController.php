<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Models\RecipeView;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(
                'can:own,recipe',
                only: ['update', 'destroy']
            ),
            new Middleware(
                'can:notOwn,recipe',
                only: ['like', 'dislike', 'removeLike']
            ),
        ];
    }

    public function index()
    {
        $recipes = Recipe::query()->withCount(['views', 'likes'])->orderBy('views_count', 'desc')->paginate();

        return ($recipes);

        return RecipeResource::collection($recipes);
    }

    public function show(Recipe $recipe)
    {
        if (auth()->id() !== $recipe->user_id) {
            RecipeView::query()->create([
                'recipe_id' => $recipe->id,
            ]);
        }

        return RecipeResource::make($recipe->loadCount(['views', 'likes'])->load(['author', 'ingredients', 'instructions', 'views']));
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

    public function like(Recipe $recipe)
    {
        $recipe->likes()->syncWithoutDetaching([auth()->id() => ['type' => 'like']]);

        return response()->json(['liked' => true]);
    }

    public function dislike(Recipe $recipe)
    {
        $recipe->likes()->syncWithoutDetaching([auth()->id() => ['type' => 'dislike']]);

        return response()->json(['disliked' => true]);
    }

    public function removeLike(Recipe $recipe)
    {
        $recipe->likes()->detach([auth()->id()]);

        return response()->json(['like_removed' => true]);
    }
}
