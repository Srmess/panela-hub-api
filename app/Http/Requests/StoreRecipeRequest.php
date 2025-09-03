<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                       => ['required', 'string'],
            'description'                => ['required', 'string'],
            'prepTime'                   => ['required', 'string'],
            'cookTime'                   => ['required', 'string'],
            'recipeYield'                => ['required', 'string'],
            'ingredients'                => ['required', 'array', 'min:1'],
            'ingredients.*.ingredient'   => ['required', 'string'],
            'instructions'               => ['required', 'array', 'min:1'],
            'instructions.*.instruction' => ['required', 'string'],
        ];
    }

    public function getValidatedPayload()
    {
        return [
            "recipe" => [
                'user_id'     => auth()->id(),
                'name'        => $this->name,
                'description' => $this->description,
                'prepTime'    => $this->prepTime,
                'cookTime'    => $this->cookTime,
                'recipeYield' => $this->recipeYield,
            ],
            "ingredients"  => $this->ingredients,
            "instructions" => $this->instructions,
        ];
    }
}
