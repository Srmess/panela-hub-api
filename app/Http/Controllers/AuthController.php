<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $payload = $request->validated();
        $user    = User::create($payload);
        $token   = $user->createToken('auth_token')->plainTextToken;

        return response()->json(compact('token'), 201);
    }

    public function login(LoginUserRequest $request)
    {
        $payload = $request->validated();

        $user = User::query()->firstWhere('email', '=', $payload['email']);

        if (! $user || ! Hash::check($payload['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(compact('token'), 200);
    }
}
