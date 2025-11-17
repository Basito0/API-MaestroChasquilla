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
}

