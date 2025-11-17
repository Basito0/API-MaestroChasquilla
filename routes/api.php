<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WorkerController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Cors;
use App\Models\Client;
use App\Models\Worker;
use App\Models\ClientRequest;
use App\Models\WorkerRequest;



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
    $request = ClientRequest::with('client.user')->find($id);

    if (!$request) {
        return response()->json(['error' => 'Request not found'], 404);
    }

    return response()->json($request);
});
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/workers/search', [CategoryController::class, 'searchWorkers']);


Route::middleware('auth:sanctum')->post('/create-client-request', function (Request $request) {
    $user = Auth::user();

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'budget' => 'required|integer|min:0',
        'street' => 'required|string|max:200',
        'city' => 'required|string|max:30',
        'region' => 'required|string|max:25',
    ]);

    $address = "{$validated['street']}, {$validated['city']}, {$validated['region']}";

    $clientRequest = ClientRequest::create([
        'client_id' => $user->client?->client_id, // now works
        'title' => $validated['title'],
        'description' => $validated['description'],
        'budget' => $validated['budget'],
        'address' => $address,
    ]);

    return response()->json([
        'message' => 'Solicitud creada exitosamente',
        'client_request' => $clientRequest
    ], 201);
});
