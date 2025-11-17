<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function updateCategories(Request $request)
    {
        $user = Auth::user();

        // Obtener el worker desde la relación plural
        $worker = $user->workers()->first();

        if (!$worker) {
            return response()->json(['message' => 'Este usuario no es maestro'], 403);
        }

        $validated = $request->validate([
            'categories' => 'array', 
            'categories.*' => 'integer|exists:categories,category_id',
        ]);

        // Sincronizar categorías
        $worker->categories()->sync($validated['categories'] ?? []);


        return response()->json([
            'message' => 'Categorías actualizadas correctamente',
            'categories' => $worker->categories
        ]);
    }

    public function searchWorkers(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string',
            'city'     => 'nullable|string',
            'region'   => 'nullable|string',
        ]);

        $query = \App\Models\User::whereHas('workers');

        // Filtrar por categoría
        if ($request->category) {
            $query->whereHas('workers.categories', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filtrar por ciudad y región
        if ($request->city) {
            $query->where('address', 'like', '%' . $request->city . '%');
        }
        if ($request->region) {
            $query->where('address', 'like', '%' . $request->region . '%');
        }

        // Cargar relación correctamente
        $users = $query->with(['workers' => function($q) {
            $q->with('categories'); 
        }])->get();

        // Mapear para que workers no sea null
        $users = $users->map(function($user) {
            if (!$user->workers) {
                $user->workers = new \stdClass();
                $user->workers->categories = [];
            } elseif (!$user->workers->categories) {
                $user->workers->categories = [];
            }
            return $user;
        });

        return response()->json($users);
    }


}

