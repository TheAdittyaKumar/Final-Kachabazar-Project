<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB'); //database connecter

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$login_error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") { //uses post cause get unsafe
    $email = $_POST['email'];
    $password = $_POST['password']; //checks if the email and pass match

    // Check if admin exists
    $admin_query = "SELECT * FROM User WHERE Uemail='$email' AND Upassword='$password' AND admin=1"; //user table shathe match
    $admin_result = mysqli_query($conn, $admin_query);
    if (mysqli_num_rows($admin_result) == 1) {
        $row = mysqli_fetch_assoc($admin_result);
        $_SESSION['admin_id'] = $row['customer_id']; //gets admins information, shows the welcome with name, restricts access to admins only
        $_SESSION['admin_name'] = $row['Uname'];
        $_SESSION['role'] = 'admin';

        header("Location: admin_dashboard.php"); //Goes to admin_dashboard
        exit();
    } else {
        $login_error = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body class="admin-page">
    <div class="login-container">
        <h2>Welcome to KachaBazar's Admin Login</h2>
        <?php if (!empty($login_error)): ?>
            <p style="color:red;"><?php echo $login_error; ?></p> <!--variable empty nah toh-->
        <?php endif; ?>

        <form method="POST" action="">
            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <input type="submit" value="Login">
        </form>

        <br>
        <p>Do not have an account? Click below and register now.</p>
        <a href="admin_register.php">Register as Admin</a> <!--registration redirecter-->
    </div>
</body>
</html>
