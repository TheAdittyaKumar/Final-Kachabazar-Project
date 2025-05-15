<?php 
session_start();
require_once 'config.php'; //user is seller, if not Kaboom to login.php, config.php to form a connection with database
if (!isset($_SESSION['seller_id'])) {
    header("Location: login.php");
    exit();
}
$seller_id = $_SESSION['seller_id'];
//distinct gets as one row per order, item combo which helps us avoid duplicate order IDs with multiple products
$sql = " SELECT DISTINCT o.order_id, o.ORdate_time, o.total_bill, o.ORpayment_status, g.Groc_name, c.quantity FROM `order` o JOIN contain c ON o.order_id = c.ORorder_id JOIN grocery_items g ON c.GRitem_id = g.item_id WHERE g.SEseller_id = ? ORDER BY o.ORdate_time DESC ";
$stmt = $conn->prepare($sql); // preventing SQL injection by binding $seller_id
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result(); //get data that matches rows of the seller
?>
<!DOCTYPE html>
<html>
<head>
    <title>Seller Orders</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="dashboard-page">
<div class="dashboard-container">
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="seller_dashboard.php">Dashboard</a></li>
            <li><a href="manage_products.php">Manage Products</a></li>
            <li><a href="view_orders_seller.php">View Orders</a></li>
            <li><a href="update_store_info.php">Update Info</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="box">
            <h2>Orders for Your Products</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date & Time</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Bill</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['order_id'] ?></td>
                            <td><?= $row['ORdate_time'] ?></td>
                            <td><?= htmlspecialchars($row['Groc_name']) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= $row['total_bill'] ?> Tk</td>
                            <td><?= $row['ORpayment_status'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
