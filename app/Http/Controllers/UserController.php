<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;


class UserController extends Controller {
    public function register(Request $request) {
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

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'role' => $roles[$role]
        ]);
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();

    $token = $user->createToken('token')->plainTextToken;

    $cookie = cookie('jwt', $token, 60 * 24); // 1 day  
    
    return response ([
        'message' => 'success',
    ])->withCookie($cookie);
}
}
