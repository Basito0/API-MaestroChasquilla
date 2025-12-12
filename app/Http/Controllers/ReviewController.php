<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Work;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'work_id'      => 'required|integer|exists:works,work_id',
            'reviewed_id'  => 'required|integer|exists:users,user_id',
            'title'        => 'required|string',
            'description'  => 'required|string',
            'score'        => 'required|integer|min:1|max:5'
        ]);

        $work = Work::with(['client.user', 'worker.user'])
            ->findOrFail($request->work_id);

        $user = auth()->user();

        // VALIDACIÓN: Solo cliente o maestro pueden reseñar
        if (
            $user->user_id !== $work->client->user->user_id &&
            $user->user_id !== $work->worker->user->user_id
        ) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        // VALIDACIÓN: El work debe estar ACEPTADO
        if ($work->state !== 'aceptado') {
            return response()->json([
                'error' => 'Solo puedes dejar una reseña cuando el trabajo ha sido aceptado.'
            ], 422);
        }

        // VALIDACIÓN: No permitir reseñar dos veces
        $already = Review::where('work_id', $work->work_id)
            ->where('reviewer_id', $user->user_id)
            ->first();

        if ($already) {
            return response()->json(['error' => 'Ya has dejado una reseña para este trabajo.'], 422);
        }

        // Crear reseña
        $review = Review::create([
            'work_id'     => $work->work_id,
            'reviewer_id' => $user->user_id,
            'reviewed_id' => $request->reviewed_id,
            'title'       => $request->title,
            'description' => $request->description,
            'score'       => $request->score
        ]);

        // ACTUALIZAR SCORE DEL USUARIO RESEÑADO
        $newScore = Review::where('reviewed_id', $request->reviewed_id)->avg('score');
        $reviewedUser = \App\Models\User::find($request->reviewed_id);
        $reviewedUser->score = $newScore;
        $reviewedUser->save();

        return response()->json([
            'message' => 'Reseña creada con éxito.',
            'review' => $review
        ], 201);
    }

    public function myReviews()
    {
        $user = auth()->user();

        $reviews = Review::where('reviewed_id', $user->user_id)
            ->with([
                'reviewer',        // quién escribió la reseña
                'work.client_request' // para mostrar el título del trabajo
            ])
            ->orderBy('review_id', 'desc')
            ->get();

        return response()->json($reviews);
    }
}
