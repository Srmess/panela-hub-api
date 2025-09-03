<?php

declare(strict_types = 1);

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

class RecipePolicy
{
    public function own(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }
}
