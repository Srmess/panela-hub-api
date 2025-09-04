<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function show()
    {
        return UserResource::make(auth()->user());
    }

    public function update(UpdateUserRequest $request)
    {
        $payload = $request->validated();

        auth()->user()->update($payload);

        return UserResource::make(auth()->user());
    }

    public function destroy()
    {
        auth()->user()->delete();

        return response()->noContent();
    }
}
