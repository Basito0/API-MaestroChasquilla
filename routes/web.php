<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/workersearch', function(){
    $servername = "localhost";
    $username = "root";
    $password = "Contraseña302;";
    $dbname = "maestrochasquilla";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM Users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "UserID: " . $row["UserID"]. " - Name: " . $row["FirstName"]. " " . $row["LastName"]. "<br>";
        }
    } else {
        echo "0 results";
    }
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
    
    $sql = "SELECT * FROM WorkerRequests";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Title: " . $row['Title'] . " - Description: " . $row['Descr'];
        }
    } else {
        echo "0 results";
    }
});

Route::get('/works', function(){
    $servername = "localhost";
    $username = "root";
    $password = "Contraseña302;";
    $dbname = "maestrochasquilla";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM Works";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "trabajos";
        }
    } else {
        echo "0 results";
    }
});