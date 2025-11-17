<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
    public function searchWorkers(Request $request)
    {
        $categoryName = $request->query('category');

        if (!$categoryName) {
            return response()->json([
                'error' => 'Debes enviar el nombre de la categoría en ?category='
            ], 400);
        }

        // buscar categoría por nombre
        $category = Category::where('name', 'LIKE', "%{$categoryName}%")->first();

        if (!$category) {
            return response()->json([
                'message' => 'No se encontró una categoría con ese nombre'
            ], 404);
        }

        // obtener los trabajadores asociados
        $workers = $category->workers()->with('user')->get();

        return response()->json([
            'category' => $category->name,
            'results' => $workers
        ]);
    }
}

