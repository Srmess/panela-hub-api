<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'prepTime'     => $this->prepTime,
            'cookTime'     => $this->cookTime,
            'recipeYield'  => $this->recipeYield,
            'updatedAt'    => $this->updated_at,
            'author'       => UserResource::make($this->whenLoaded('author')),
            'ingredients'  => IngredientResource::collection($this->whenLoaded('ingredients')),
            'instructions' => InstructionResource::collection($this->whenLoaded('instructions')),
            'views_count'  => $this->views_count ?? null,
            'likes_count'  => $this->likes_count ?? null,
        ];
    }
}
