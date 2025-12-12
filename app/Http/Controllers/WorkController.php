<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    // Trabajos que ha solicitado el cliente
    public function myWorks(Request $request)
    {
        $user = auth()->user();

        if ($user->client) {
            $works = Work::where('client_id', $user->client->client_id)
                ->where('state', 'aceptado')
                ->with(['client', 'worker', 'client_request'])
                ->get();

            return response()->json($works);
        }

        return response()->json(['error' => 'No tienes acceso a esta información.'], 403);
    }

    // Trabajos que ha realizado el maestro
    public function myWorkedJobs(Request $request)
    {
        $user = auth()->user();

        if ($user->workers) {
            $works = Work::where('worker_id', $user->workers->worker_id)
                ->where('state', 'aceptado') 
                ->with(['client', 'worker', 'client_request'])
                ->get();

            return response()->json($works);
        }

        return response()->json(['error' => 'No tienes acceso a esta información.'], 403);
    }
}

