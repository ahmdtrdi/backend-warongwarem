<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request data
        $this->validator($request->all())->validate();

        // Create a new user and fire the Registered event
        event(new Registered($user = $this->create($request->all())));

        // Log the user in
        auth()->login($user);

        // Return a successful response
        return $this->registered($request, $user)
                    ?: redirect($this->redirectPath());
    }

    protected function validator(array $data)
    {
        // Define your validation rules here
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'integer', 'between:1,3'],
        ]);
    }

    protected function create(array $data)
    {
        // Map indices to roles
        $roles = [1 => 'customer', 2 => 'waiter', 3 => 'manager'];

        return User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $roles[$data['role']],
        ]);
    }

    protected function registered(Request $request, $user)
    {
        // Generate a token for the user and return a successful response
        $user->generateToken();

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'role' => $user->role,
        ], 201);
    }

    protected function redirectPath()
    {
        // Define your redirect path here
        return '/Login';
    }
}
