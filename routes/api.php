<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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