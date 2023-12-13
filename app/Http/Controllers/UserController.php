<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller {
    public function register(Request $request) {
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'error' => 'Email already exists',
            ], 422);
        }

        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // Map indices to roles
        $roles = [1 => 'customer', 2 => 'waiter', 3 => 'manager'];

        // Set role from request data
        // $user->role = $roles[$request->role];
        $user->role = $roles[$request->get('role', 1)];

        $user->save();

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            // 'role' => $roles[$request->role]
            'role' => $roles[$request->get('role', 1)]
        ]);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)) {
            // Authentication passed...
            $user = Auth::user();
            $token = $user->createToken('token-name', [], now()->addDays(7));

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        // Authentication failed...
        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}
