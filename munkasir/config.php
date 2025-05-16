<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "productdb";
    
    // Attempt to connect to the database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

?>
