<?php
session_start();
if (!isset($_SESSION['seller_id']) || $_SESSION['role'] != 'seller') {
    header("Location: login.php");
    exit();
}

// DATABASE ZOOOOOOOOOOOOOOOOOOM
$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Get the seller information
$seller_id = $_SESSION['seller_id'];
$seller_query = "SELECT store_name, store_location, store_description FROM Seller WHERE seller_id = $seller_id";
$seller_result = mysqli_query($conn, $seller_query);
$seller = mysqli_fetch_assoc($seller_result);
// Gets the reviews using this
$review_query = "SELECT * FROM `Ratings&Review` WHERE SEseller_id = $seller_id ORDER BY Rdate_time DESC";
$review_result = mysqli_query($conn, $review_query);
// This will help us calculate average rating
$avg_query = "SELECT AVG(rating) AS average_rating FROM `Ratings&Review` WHERE SEseller_id = $seller_id";
$avg_result = mysqli_query($conn, $avg_query);
$avg_row = mysqli_fetch_assoc($avg_result);
$average_rating = $avg_row['average_rating'];
// Marks the orders if completed
if (isset($_GET['complete_order_id'])) {
    $order_id = intval($_GET['complete_order_id']);
    $update_status = "UPDATE `order` SET ORpayment_status='Completed' WHERE order_id=$order_id";
    mysqli_query($conn, $update_status);
}
// Get the Orders placed to this seller
$order_query = "
    SELECT o.order_id, o.ORdate_time, o.total_bill, o.ORpayment_status, u.Uname, g.Groc_name, c.quantity FROM `order` o JOIN `contain` c ON o.order_id = c.ORorder_id JOIN `grocery_items` g ON c.GRitem_id = g.item_id JOIN `user` u ON u.customer_id = o.UScustomer_id WHERE g.SEseller_id = $seller_id ORDER BY o.ORdate_time DESC";
$order_result = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        .btn { padding: 6px 10px; background: green; color: white; border: none; cursor: pointer; }
        .btn:hover { background: darkgreen; }
    </style>
</head>
<body class="dashboard-page">
<div class="dashboard-container">
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="manage_products.php">Manage Products</a></li>
            <li><a href="view_orders_seller.php">View Orders</a></li>
            <li><a href="update_store_info.php">Update Store Info</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
    <div class="fixed-width-wrapper">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['seller_name']); ?>!</h1>
        <div class="store-info box seller-details-box">
            <div class="seller-info">
                <h2>Your Store Details:</h2>
                <p><strong>Store Name:</strong> <?php echo htmlspecialchars($seller['store_name']); ?></p>
                <p><strong>Store Location:</strong> <?php echo htmlspecialchars($seller['store_location']); ?></p>
                <p><strong>Store Description:</strong> <?php echo htmlspecialchars($seller['store_description']); ?></p>
            </div>
        </div>
        <?php if ($seller_id == 1): ?>
        <div class="store-cover">
            <img src="cover_seller_store_1.png" alt="Store Cover">
        </div>
        <?php endif; ?>
        <div class="rating-info box">
            <h2>Overall Customer Rating:</h2>
            <?php
            if ($average_rating) {
                echo "<p><span class='rating-star'>★</span> " . round($average_rating, 1) . "/5</p>";
            } else {
                echo "<p>No ratings yet</p>";
            }
            ?>
        </div>
        <div class="reviews-info box">
            <h2>Customer Reviews:</h2>
            <table>
                <thead>
                    <tr>
                        <th>Review ID</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($review_result) > 0) {
                        while ($review = mysqli_fetch_assoc($review_result)) {
                            echo "<tr>";
                            echo "<td>{$review['review_id']}</td>";
                            echo "<td>{$review['rating']}</td>";
                            echo "<td>" . htmlspecialchars($review['review']) . "</td>";
                            echo "<td>{$review['Rdate_time']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No reviews available yet!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- LOOOOOOK orders table -->
        <div class="orders-info box">
            <h2>Customer & Admin Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($order_result)): ?>
                        <tr>
                            <td><?= $row['order_id'] ?></td>
                            <td><?= htmlspecialchars($row['Uname']) ?></td>
                            <td><?= htmlspecialchars($row['Groc_name']) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= $row['total_bill'] ?> Tk</td>
                            <td><?= $row['ORpayment_status'] ?></td>
                            <td><?= $row['ORdate_time'] ?></td>
                            <td>
                                <?php if ($row['ORpayment_status'] == 'Pending'): ?>
                                    <a class="btn" href="seller_dashboard.php?complete_order_id=<?= $row['order_id'] ?>">Mark Complete</a>
                                <?php else: ?>
                                    ✅
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
</html>

