<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
</head>
<body>
    <h2>Admin Registration</h2>
    <form action="" method="POST">
        <label>Name:</label><br>
        <input type="text" name="uname" required><br><br>
        <label>Phone Number:</label><br>
        <input type="text" name="phone" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>

<?php
$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB'); //database connecter
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST['uname'];
    $phone = $_POST['phone']; //form data reader
    $email = $_POST['email'];
    $password = $_POST['password'];
    $check_query = "SELECT * FROM User WHERE Uemail='$email'"; //check if email already exists or not
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) { //error if the email already exists
        echo "Email already registered!";
    } else {
        // Insert this person into User table with admin=1, customer=0. Essentially using a flag system here.
        $sql = "INSERT INTO User (Uname, PhoneNo, Upassword, Uemail, admin, customer) 
                VALUES ('$uname', '$phone', '$password', '$email', 1, 0)";

        if (mysqli_query($conn, $sql)) {
            header("Location: admin_login.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
