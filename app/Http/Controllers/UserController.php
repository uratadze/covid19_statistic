<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * create new user and generate new access token.
     *
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->getPassword()
        ]);

        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => [
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_token' => $user->createToken('access-token')->plainTextToken
            ]
        ]);
    }

    /**
     * generate new access token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!auth()->attempt($request->validated()))
        {
            return response()->json(['status' => 401, 'success' => true, 'message' => 'Incorrect information'], 401);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => [
                'user_token' => User::whereEmail($request->email)
                    ->first()
                    ->createToken('access-token')
                    ->plainTextToken
            ]
        ]);
    }
}
