<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Cors;
use App\Models\Client;
use App\Models\Worker;

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    $user = Auth::user(); // usuario autenticado por el token

    return response()->json($user);
});

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
    } elseif ($user->clients) {
        return response()->json(['type' => 1]);
    }

    return response()->json(['type' => null]);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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

Route::options('/clientrequests', function () {
    return response('', 204)
        ->header('Access-Control-Allow-Origin', 'http://localhost:5173')
        ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});


Route::get('/clientrequests', function () {
    $conn = new mysqli("localhost", "root", "850221B", "maestrochasquilla");

    if ($conn->connect_error) {
        return response()->json(['error' => 'Connection failed'], 500);
    }

    $sql = "SELECT * FROM client_requests";
    $result = $conn->query($sql);

    if (!$result) {
        return response()->json(['error' => 'Query failed'], 500);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return response()->json($data);
});

Route::get('/workerrequests', function(){
    $servername = "localhost";
    $username = "root";
    $password = "850221B";
    $dbname = "maestrochasquilla";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM worker_requests";
    $result = $conn->query($sql);

    if (!$result) {
        return response()->json(['error' => 'Query failed'], 500);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return response()->json($data);
});