<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        
        try {
            $validated = $request->validate([
                'fname' => 'required|string',
                'lname' => 'required|string',
                'phone' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);
            $user = User::create([
                'first_name' => $validated['fname'],
                'last_name' => $validated['lname'],
                'phone_number' => $validated['phone'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'profile_path' => 'default.jpg',
                'score' => 0,
            ]);

            return response()->json(['message' => 'User created successfully', 'user' => $user]);
        } catch (\Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

