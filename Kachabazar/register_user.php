<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
<div class="container">
    <h2>Register as a KachaBazar Customer!</h2>
    <h3>We believe customer is the king.</h3>
    <form action="" method="POST">
        <label>Name:</label>
        <input type="text" name="uname" required><br><br>

        <label>Phone Number:</label>
        <input type="text" name="phone" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Register"><br><br>
    </form>
</div>
</body>
</html>


<?php
// database zoooooom
$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// customerName,phone,email,password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST['uname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Insert into User table (customer registration). So customer is 1 and admin is 0
    $sql = "INSERT INTO User (Uname, PhoneNo, Upassword, Uemail, admin, customer) 
            VALUES ('$uname', '$phone', '$password', '$email', 0, 1)";

    if (mysqli_query($conn, $sql)) {
        // After successful registration redirect to login page
        header("Location: login.php");
        exit();
    } else { //kaboom
        echo "Error: " . mysqli_error($conn);
    }
}
?>