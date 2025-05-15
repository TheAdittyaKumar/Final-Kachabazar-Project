<?php
session_start(); // session
if (!isset($_SESSION['customer_id']) || $_SESSION['role'] != 'customer') { //Checks if the user is not logged in as customer
    header("Location: login.php"); //If Kaboom then go back to login.
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['Uname']; ?>!</h2> <!--Take name from database.-->
    <p>You are logged in as a Customer.</p>
    <ul>
        <li><a href="view_products.php">View Grocery Items</a></li>
        <li><a href="view_orders.php">My Orders</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
