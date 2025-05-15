<?php // Creates a connection and reuses it across files. Should have done it before.
$servername = "localhost";
$username = "root";
$password = "";
$database = "kachabazarDB";

// Created connection with database lets go
$conn = new mysqli($servername, $username, $password, $database);

// Kaboom cant reach database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
