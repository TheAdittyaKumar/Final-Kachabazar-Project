<?php
session_start();
require_once "config.php"; // DB connecter

$sql = "
    SELECT o.order_id, o.ORdate_time, o.total_bill, o.ORpayment_status, u.Uname AS customer_name, g.Groc_name, g.SEseller_id, s.store_name, c.quantity FROM `order` o JOIN user u ON o.UScustomer_id = u.customer_id JOIN contain c ON o.order_id = c.ORorder_id JOIN grocery_items g ON c.GRitem_id = g.item_id JOIN seller s ON g.SEseller_id = s.seller_id ORDER BY o.ORdate_time DESC
";
//Selecting data from 5 different tables like
//order details like date, bill, payment state, user details like connect customer_id to customer name, M-N relationship, 
//item details and links it to seller, contains the store info and seller name too.
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="dashboard-page">
<div class="dashboard-container">
    <div class="sidebar"> <!--NAV-->
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php">Manage Customers</a></li>
            <li><a href="manage_sellers.php">Manage Sellers</a></li>
            <li><a href="manage_orders.php">Manage Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="box">
            <h2>All Orders in the System</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Seller</th>
                        <th>Item</th>
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
                            <td><?= htmlspecialchars($row['customer_name']) ?></td> <!--gets data from database table using $row-->
                            <td><?= htmlspecialchars($row['store_name']) ?> (ID: <?= $row['SEseller_id'] ?>)</td>
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
