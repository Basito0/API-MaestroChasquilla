<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function updateCategories(Request $request)
    {
        $user = Auth::user();
        $worker = $user->worker;

        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,category_id',
        ]);

        $worker->categories()->sync($validated['categories']);

        return response()->json([
            'message' => 'Categorías actualizadas correctamente',
            'categories' => $worker->categories
        ]);
    }
}

