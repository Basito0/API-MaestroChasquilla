<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use App\Models\Client;

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
                'type' => 'required|in:1,2',
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

            if ($validated['type'] == 1) {
                Client::create([
                    'user_id' => $user->user_id,
                ]);
            } else {
                Worker::create([
                    'user_id' => $user->user_id,
                ]);
            }

            return response()->json([
                'user' => $user,
                'message' => 'User created successfully', 'user' => $user,
            ]);

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

                // Create a token for stateless authentication
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message' => 'Login exitoso',
                    'user' => $user,
                    'token' => $token,
                ]);
            }

            return response()->json(['message' => 'Credenciales inválidas'], 401);

        } catch (\Throwable $e) {
            Log::error('Login error: '.$e->getMessage());
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'fname'  => 'required|string',
                'lname'  => 'required|string',
                'phone'  => 'required|string',
                'address'=> 'required|string',
            ]);

            $user = Auth::user();

            $user->update([
                'first_name'   => $validated['fname'],
                'last_name'    => $validated['lname'],
                'phone_number' => $validated['phone'],
                'address'      => $validated['address'],
            ]);

            return response()->json([
                'message' => 'Perfil actualizado correctamente',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getProfile(Request $request)
    {
        $user = $request->user(); // usuario autenticado
        $user->load(['workers.categories']); // carga worker y sus categorías
        return response()->json($user);
    }


}

