<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return response()->json(['message' => 'Login exitoso', 'user' => $user]);
            }

            return response()->json(['message' => 'Credenciales inválidas'], 401);

        } catch (\Throwable $e) {
            Log::error('Login error: '.$e->getMessage());
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }
}

