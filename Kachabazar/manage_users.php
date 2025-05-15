<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin') { //only admin access
    header("Location: admin_login.php");
    exit();
}
$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB'); //DATABASE ZOOOM
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Handle delete user
if (isset($_POST['delete_user'])) {
    $customer_id = $_POST['customer_id'];
    // Find all order ids made by this user. Uses UScustomer_id in Order table to refer to users.
    $order_query = "SELECT order_id FROM `Order` WHERE UScustomer_id = $customer_id";
    $order_result = mysqli_query($conn, $order_query);
    $order_ids = [];
    while ($row = mysqli_fetch_assoc($order_result)) {
        $order_ids[] = $row['order_id'];
    }
    // Delete those orders from Contain table (connects Order and Grocery_items)
    if (!empty($order_ids)) {
        $order_ids_str = implode(',', $order_ids);
        mysqli_query($conn, "DELETE FROM Contain WHERE ORorder_id IN ($order_ids_str)"); //lets delete FK first before going to Order
    }
    // Deleting orders from Order table linked to that customer
    mysqli_query($conn, "DELETE FROM `Order` WHERE UScustomer_id = $customer_id");
    // Step 4: Delete user roles (not really using it though kept incase)
    mysqli_query($conn, "DELETE FROM User_Role WHERE UScustomer_id = $customer_id");

    // Delete user. Kaboom 
    mysqli_query($conn, "DELETE FROM User WHERE customer_id = $customer_id");
}

// get the users so admin can view the customer names, phone, delete them if needed
$users = mysqli_query($conn, "SELECT * FROM User WHERE customer = 1");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Customers</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_sellers.php">Manage Sellers</a></li>
            <li><a href="manage_users.php">Manage Customers</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Manage Customers</h2>
        <div class="box">
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($users)) { ?>
                <tr>
                    <td><?php echo $row['customer_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['Uname']); ?></td>
                    <td><?php echo htmlspecialchars($row['PhoneNo']); ?></td>
                    <td><?php echo htmlspecialchars($row['Uemail']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="customer_id" value="<?php echo $row['customer_id']; ?>">
                            <button type="submit" name="delete_user" class="delete-button" onclick="return confirm('Are you sure you want to delete this user and all related data?');">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
