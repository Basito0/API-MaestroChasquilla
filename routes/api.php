<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Cors;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user/{id}', function ($id) {
    $servername = "localhost";
    $username = "root";
    $password = "Contraseña302;";
    $dbname = "maestrochasquilla";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM Users WHERE UserID =" . $id;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "UserID: " . $row["UserID"]. " - Name: " . $row["FirstName"]. " " . $row["LastName"]. "<br>";
        }
    } else {
        echo "0 results";
    }
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
    $conn = new mysqli("localhost", "root", "Contraseña302;", "maestrochasquilla");

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
    $password = "Contraseña302;";
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