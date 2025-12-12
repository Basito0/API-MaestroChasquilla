<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Models\Work;

class ClientRequestController extends Controller
{
    public function selectMaster(Request $request, $id)
    {
        // Validar trabajo enviado
        $request->validate([
            'work_id' => 'required|integer|exists:works,work_id',
        ]);

        // Cargar solicitud del cliente con sus postulaciones
        $clientRequest = ClientRequest::with('works')->findOrFail($id);

        // Validar dueño de la solicitud
        if (auth()->user()->client?->client_id !== $clientRequest->client_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Work seleccionado
        $selectedWork = $clientRequest->works
            ->firstWhere('work_id', $request->work_id);

        if (!$selectedWork) {
            return response()->json([
                'error' => 'El work no pertenece a esta solicitud'
            ], 422);
        }

        // Validar que no haya maestro seleccionado
        if ($clientRequest->selected_work_id) {
            return response()->json([
                'error' => 'Ya existe un maestro seleccionado'
            ], 422);
        }

        // Guardar selección
        $clientRequest->selected_work_id = $selectedWork->work_id;
        $clientRequest->selected_worker_id = $selectedWork->worker_id;
        $clientRequest->save();

        // Aceptar este work
        $selectedWork->state = 'aceptado';
        $selectedWork->save();

        // Rechazar los demás
        Work::where('client_request_id', $clientRequest->client_request_id)
            ->where('work_id', '!=', $selectedWork->work_id)
            ->update(['state' => 'rechazado']);

        return response()->json([
            'message' => 'Maestro seleccionado con éxito',
            'client_request' => $clientRequest,
            'accepted_work' => $selectedWork
        ]);
    }

    public function show($id)
    {
        $clientRequest = ClientRequest::with([
            'works.worker.user',        
            'client.user',
            'category'
        ])->findOrFail($id);

        return response()->json($clientRequest);
    }

}
