<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php"); //Not admin redirect 
    exit();
}
$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB'); //zpoom database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$admin_id = $_SESSION['admin_id']; //admins info, name, phone, pass from User table
$admin_query = "SELECT * FROM User WHERE customer_id = $admin_id AND admin = 1";
$admin_result = mysqli_query($conn, $admin_query);
$admin_data = mysqli_fetch_assoc($admin_result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //form submitted??!
    $new_name = mysqli_real_escape_string($conn, $_POST['new_name']);
    $new_phone = mysqli_real_escape_string($conn, $_POST['new_phone']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
 //update the name,phone,pass in users table for this admin
    $update_query = "UPDATE User SET Uname = '$new_name', PhoneNo = '$new_phone', Upassword = '$new_password' WHERE customer_id = $admin_id";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['admin_name'] = $new_name; //Updates session variable so the new name reflects immediately in the UI.
        echo "<script>alert('Profile updated successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Admin Info</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="admin-page">
<div class="dashboard-container">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_sellers.php">Manage Sellers</a></li>
            <li><a href="manage_orders.php">Manage Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h2>Update Your Information</h2>
        <div class="update-form">
            <form method="POST" action="">
                <label for="new_name">New Name:</label>
                <input type="text" name="new_name" id="new_name" value="<?php echo htmlspecialchars($admin_data['Uname']); ?>" required>
                <label for="new_phone">New Phone Number:</label>
                <input type="text" name="new_phone" id="new_phone" value="<?php echo htmlspecialchars($admin_data['PhoneNo']); ?>" required>
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" value="<?php echo htmlspecialchars($admin_data['Upassword']); ?>" required>
                <input type="submit" value="Update Info">
            </form>
        </div>
    </div>
</div>
</body>
</html>
