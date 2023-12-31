<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'error' => 'E-mail telah terdaftar',
            ], 422);
        }

        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // Map indices to roles
        $roles = [1 => 'customer', 2 => 'waiter', 3 => 'manager'];

        //set default value role
        $role = 1;

        if (Str::endsWith($request->email, '@warongwarem.co.id')) {
            $role = 3; // manager
        } elseif (Str::endsWith($request->email, '@waiters.warongwarem.co.id')) {
            $role = 2; // waiter
        }
        // Set role
        $user->role = $roles[$role];
        $user->save();

        // Generate a token for the new user
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'role' => $roles[$role],
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    // Login Controller
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
