<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>Login page</title>
</head>
<body class="login-page">
<div class="login-container">
    <h2>Welcome to KachaBazar!</h2>
    <form method="POST" action="login.php">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>
    <p>Do not have an account? Click below and register now.</p>
    <a href="register_user.php">Register as Customer</a> |
    <a href="register_seller.php">Register as Seller</a> <!--register using these links-->
</div>
</body>
</html>

<?php
session_start(); // session beginssssss
$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB'); //database connected
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { //checks if the form has been submitted
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_query = "SELECT * FROM User WHERE Uemail='$email' AND Upassword='$password'"; //Check User table 
    $user_result = mysqli_query($conn, $user_query);

    if (mysqli_num_rows($user_result) == 1) {
       
        $row = mysqli_fetch_assoc($user_result); //if user found then
        $_SESSION['customer_id'] = $row['customer_id'];
        $_SESSION['Uname'] = $row['Uname'];
        $_SESSION['role'] = ($row['admin'] == 1) ? 'admin' : 'customer'; //ternary var. if admin login admin or go to customer dash
        // role is important here
        if ($_SESSION['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: customer_dashboard.php");
        }
        exit();
    } else {
        // If not found in User, check in Seller
        $seller_query = "SELECT * FROM Seller WHERE seller_email='$email' AND seller_password='$password'";
        $seller_result = mysqli_query($conn, $seller_query);
        if (mysqli_num_rows($seller_result) == 1) {
            $row = mysqli_fetch_assoc($seller_result);
            $_SESSION['seller_id'] = $row['seller_id']; //seller found then sets these
            $_SESSION['seller_name'] = $row['seller_name'];
            $_SESSION['role'] = 'seller';
            header("Location: seller_dashboard.php");
            exit();
        } else {
            echo "Invalid Email or Password!"; //kaboom moment
        }
    }
}
?>