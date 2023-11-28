<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $user = new User;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        
        // Map indices to roles
        $roles = [1 => 'customer', 2 => 'waiter', 3 => 'manager'];

        // Set role from request data
        $user->role = $roles[$request->role];
        
        $user->save();

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ]);
    }
}
