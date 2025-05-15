<!DOCTYPE html>
<html>
<head>
    <title>Seller Registration</title>
</head>
<body>
    <h2>Register as a Seller!</h2>
    <h3>Start earning now with KachaBazar! Success awaits!</h3>
    <form action="" method="POST">
        <label>Seller Name:</label><br>
        <input type="text" name="seller_name" required><br><br>
        <label>Store Name:</label><br>
        <input type="text" name="store_name" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="seller_email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="seller_password" required><br><br>
        <label>Store Location:</label><br>
        <input type="text" name="store_location" required><br><br>
        <label>Store Description:</label><br>
        <textarea name="store_description" rows="4" cols="50" required></textarea><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>

<?php
$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB'); // DATABASE ZOOOOOM
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") { //check if form submitted
    $seller_name = $_POST['seller_name'];
    $store_name = $_POST['store_name'];
    $seller_email = $_POST['seller_email']; //inputs from form
    $seller_password = $_POST['seller_password'];
    $store_location = $_POST['store_location'];
    $store_description = $_POST['store_description'];
    $date = date('Y-m-d');
    //check if email already exists
    $check_query = "SELECT * FROM Seller WHERE seller_email='$seller_email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "Email already registered! Please login or use a different email.";
    } else {
        // this allows us to insert a new seller
        $sql = "INSERT INTO Seller (seller_name, store_name, seller_email, seller_password, date, store_location, store_description)
                VALUES ('$seller_name', '$store_name', '$seller_email', '$seller_password', '$date', '$store_location', '$store_description')";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php"); //success looks like this
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>