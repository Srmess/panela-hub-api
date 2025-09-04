<?php

declare(strict_types = 1);

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecipePolicy
{
    public function own(User $user, Recipe $recipe)
    {
        return $user->id === $recipe->user_id
        ? Response::allow()
        : Response::deny("Only recipe author can do this.");
    }

    public function notOwn(User $user, Recipe $recipe)
    {
        return $user->id !== $recipe->user_id
        ? Response::allow()
        : Response::deny("You can't do this as recipe author.");
    }
}
