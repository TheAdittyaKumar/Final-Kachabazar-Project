<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
} // This is checking is the admin is logged in. $_SESSION part is storing the customer_id from User table. Redirects if not.

$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB'); // connect to database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// This is using the customer_id to get the admin details.
$admin_id = $_SESSION['admin_id'];
$admin_query = "SELECT * FROM User WHERE customer_id = $admin_id AND admin = 1";
$admin_result = mysqli_query($conn, $admin_query);
$admin_data = mysqli_fetch_assoc($admin_result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title> <!--admin photo aligns nicely due to this wasnt working in css file-->
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .admin-details-box { 
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 40px;
        }

        .admin-info {
            flex: 1;
        }

        .admin-photo {
            width: 250px;
            height: 250px;
            border: 3px solid black;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
        }

        .admin-photo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar"> <!--Side menu essentially-->
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_sellers.php">Manage Sellers</a></li>
            <li><a href="manage_orders.php">Manage Orders</a></li>
            <li><a href="admin_browse_sellers.php">Browse Sellers</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h2>
        <p>You are logged in as an Admin.</p>

        <div class="box admin-details-box">
            <div class="admin-info">
                <h3>Your Admin Details</h3> <!--these help get the names and numbers details from database-->
                <p><strong>Admin ID:</strong> <?php echo $admin_data['customer_id']; ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($admin_data['Uname']); ?></p> 
                <p><strong>Email:</strong> <?php echo htmlspecialchars($admin_data['Uemail']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($admin_data['PhoneNo']); ?></p>
                <br>
                <a href="update_admin_info.php" class="action-button">Update Your Info</a>
            </div>
            <div class="admin-photo">
                <img src="Admin_photo.jpg" alt="Admin Photo">
            </div>
        </div>
    </div>
</div>
</body>
</html>
