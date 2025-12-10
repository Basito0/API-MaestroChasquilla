<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\WorkerVerificationController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Cors;
use App\Models\Client;
use App\Models\User;
use App\Models\Worker;
use App\Models\ClientRequest;
use App\Models\WorkerRequest;
use App\Models\Conversation;
use App\Models\Moderator;
use App\Models\Message;
use App\Models\Work;



//Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
  //  $user = Auth::user(); // usuario autenticado por el token

    //return response()->json($user);
//});

Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'getProfile']);


Route::middleware('auth:sanctum')->put('/profile/update', [UserController::class, 'updateProfile']);


Route::middleware('auth:sanctum')->post('/logout', function () {
    $user = Auth::user();

    // Delete the current access token
    $user->currentAccessToken()->delete();

    return response()->json(['message' => 'Logout exitoso']);
});

Route::middleware('auth:sanctum')->delete('/users/{id}', function ($id) {
    $user = Auth::user();

    try {
        // Verificar que el usuario autenticado sea moderador
        if (!$user->moderator) {
            return response()->json(['error' => 'Usuario no tiene permisos.'], 403);
        }

        $target = User::find($id);

        if (!$target) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        }

        $target->delete();

        return response()->json(['message' => 'Usuario eliminado exitosamente']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al eliminar usuario', 'details' => $e->getMessage()], 500);
    }
});

Route::middleware('auth:sanctum')->delete('/client_requests/{id}', function ($id) {
    $user = Auth::user();

    try {
        // Verificar que el usuario autenticado sea moderador
        if (!$user->moderator) {
            return response()->json(['error' => 'Usuario no tiene permisos.'], 403);
        }

        $target = ClientRequest::find($id);

        if (!$target) {
            return response()->json(['error' => 'Post no encontrado.'], 404);
        }

        $target->delete();

        return response()->json(['message' => 'Post eliminado exitosamente']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al eliminar Post', 'details' => $e->getMessage()], 500);
    }
});

Route::middleware('auth:sanctum')->get('/get_users', function () {
    $user = Auth::user();

    try {
        if ($user->moderator) { // relación definida en User
            $users = User::all();
            return response()->json($users);
        } else {
            return response()->json(['error' => 'Usuario no tiene permisos.'], 403);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Query failed', 'details' => $e->getMessage()], 500);
    }
});


Route::middleware('auth:sanctum')->get('/user/type', function () {
    $user = Auth::user();

    if ($user->workers) {
        return response()->json(['type' => 2]);
    } elseif ($user->client) {
        return response()->json(['type' => 1]);
    } elseif ($user->moderator) {
        return response()->json(['type' => 3]);
    }

    return response()->json(['type' => null]);
});

Route::middleware('auth:sanctum')->post('/support_chat', function () {
    $user = Auth::user();

    try {
        // Verificar si ya existe una conversación
        $conversation = $user->conversations()->first();

        if (!$conversation) {

            // Buscar un moderador disponible
            $moderator = Moderator::inRandomOrder()->first();

            if (!$moderator) {
                return response()->json([
                    'error' => 'No hay moderadores disponibles'
                ], 500);
            }

            $conversation = Conversation::create([
                'user_id' => $user->user_id,
                'mod_id' => $moderator->mod_id,
            ]);

            return response()->json([
                'message' => 'Conversación creada',
                'conversation' => $conversation,
            ], 201);
        }

        return response()->json([
            'message' => 'Ya existe una conversación',
            'conversation' => $conversation,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al crear conversación',
            'details' => $e->getMessage()
        ], 500);
    }
});

Route::middleware('auth:sanctum')->get('/conversations/{id}/messages', function ($id) {
    $user = Auth::user();

    try {
        $conversation = Conversation::findOrFail($id);

        if ($conversation->user_id !== $user->user_id && $conversation->mod_id !== optional($user->moderator)->mod_id) {
            return response()->json([
                'error' => 'No tienes acceso a esta conversación',
                'conversation_user_id' => $conversation->user_id,
                'auth_user_id' => $user->user_id,
                'conversation_mod_id' => $conversation->mod_id,
                'auth_mod_id' => $user->moderator->mod_id,
            ], 403);
        }

        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

        return response()->json($messages);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al obtener mensajes', 'details' => $e->getMessage()], 500);
    }
});

Route::middleware('auth:sanctum')->post('/conversations/{id}/messages', function (Request $request, $id) {
    $user = Auth::user();

    try {
        $conversation = Conversation::findOrFail($id);

        if ($conversation->user_id !== $user->user_id && $conversation->mod_id !== optional($user->moderator)->mod_id) {
            return response()->json([
                'error' => 'No tienes acceso a esta conversación',
                'conversation_user_id' => $conversation->user_id,
                'auth_user_id' => $user->user_id,
                'conversation_mod_id' => $conversation->mod_id,
                'auth_mod_id' => $user->moderator->mod_id,
            ], 403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->conversation_id,
            'sender_id' => $user->user_id,
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'message' => 'Mensaje enviado',
            'data' => $message
        ], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al enviar mensaje', 'details' => $e->getMessage()], 500);
    }
});

#Endpoint para moderador
Route::middleware('auth:sanctum')->get('/mod_conversations', function (Request $request) {
    $user = Auth::user();

    try {
        $conversations = Conversation::where('mod_id', $user->moderator->mod_id)->get();

        return response()->json([
            'message' => 'Conversaciones recuperadas',
            'data' => $conversations
        ], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al enviar mensaje', 'details' => $e->getMessage()], 500);
    }
});

#Route::get('/user', function (Request $request) {
 #   return $request->user();
#})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    $user->load('workers.categories'); // carga el worker y sus categorías
    return response()->json($user);
});

Route::get('/user/{id}', function ($id) {
    $servername = "localhost";
    $username = "root";
    $password = "850221B";
    $dbname = "maestrochasquilla";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM users WHERE user_id =" . $id;
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return response()->json($data);
});

Route::post('/signup', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->post('/worker/categories', [WorkerController::class, 'updateCategories']);

Route::options('/clientrequests', function () {
    return response('', 204)
        ->header('Access-Control-Allow-Origin', 'http://localhost:5173')
        ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});


Route::get('/clientrequests', function () {
    try {
        $requests = ClientRequest::all();

        return response()->json($requests);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Query failed', 'details' => $e->getMessage()], 500);
    }
});

Route::get('/workerrequests', function () {
    try {
        $requests = WorkerRequest::all();

        return response()->json($requests);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Query failed',
            'details' => $e->getMessage()
        ], 500);
    }
});

Route::get('/clientrequests/{id}', function ($id) {
    $request = ClientRequest::with('client.user', 'category')->find($id);

    if (!$request) {
        return response()->json(['error' => 'Request not found'], 404);
    }

    return response()->json($request);
});
Route::get('/categories', [CategoryController::class, 'index']);
// regiones
Route::get('/regions', [RegionController::class, 'index']);
// comunas de una región
Route::get('/regions/{region_id}/communes', [RegionController::class, 'communes']);

Route::middleware('auth:sanctum')->get('/search/workers', [WorkerController::class, 'searchWorkers']);


Route::middleware('auth:sanctum')->post('/create-client-request', function (Request $request) {
    $user = Auth::user();

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'budget' => 'required|integer|min:0',
        'street' => 'required|string|max:200',
        'city' => 'required|string|max:30',
        'region' => 'required|string|max:25',
        'category_id' => 'nullable|integer|exists:categories,category_id',
    ]);

    $address = "{$validated['street']}, {$validated['city']}, {$validated['region']}";

    $clientRequest = ClientRequest::create([
        'client_id' => $user->client?->client_id, // now works
        'title' => $validated['title'],
        'description' => $validated['description'],
        'budget' => $validated['budget'],
        'address' => $address,
        'category_id' => $validated['category_id'],
    ]);

    return response()->json([
        'message' => 'Solicitud creada exitosamente',
        'client_request' => $clientRequest
    ], 201);
});

Route::middleware('auth:sanctum')->post('/create_work', function (Request $request) {
    $user = Auth::user();

    $validated = $request->validate([
        'client_id' => 'required|integer|exists:clients,client_id',
        'client_request_id' => 'required|integer|exists:client_requests,client_request_id',
        'state' => 'required|string',
    ]);

    // worker_id viene del usuario autenticado
    $work = Work::create([
        'client_id' => $validated['client_id'],
        'worker_id' => $user->workers?->worker_id,   // el worker autenticado
        'client_request_id' => $validated['client_request_id'],
        'state' => $validated['state'],
    ]);

    return response()->json([
        'message' => 'Solicitud creada exitosamente',
        'work' => $work
    ], 201);
});
