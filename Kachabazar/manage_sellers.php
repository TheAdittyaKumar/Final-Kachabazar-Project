<?php
session_start(); //SESSSION ZOOOOOM
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}


$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB'); //DATABASE ZOOOOOOM
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle delete seller
if (isset($_POST['delete_seller'])) {
    $seller_id = $_POST['seller_id']; 

    // Find all item_ids of this seller from grocery_items.
    $items_query = "SELECT item_id FROM Grocery_items WHERE SEseller_id = $seller_id";
    $items_result = mysqli_query($conn, $items_query);

    $item_ids = [];
    while ($row = mysqli_fetch_assoc($items_result)) {
        $item_ids[] = $row['item_id'];
    }

    // Delete those items from Contain (acting as a mediator for orders) table. FK doesnt break.
    if (!empty($item_ids)) {
        $item_ids_str = implode(',', $item_ids);
        mysqli_query($conn, "DELETE FROM Contain WHERE GRitem_id IN ($item_ids_str)");
    }
    // Delete products from Grocery_items
    mysqli_query($conn, "DELETE FROM Grocery_items WHERE SEseller_id = $seller_id");
    // Delete reviews from Ratings&Review
    mysqli_query($conn, "DELETE FROM `Ratings&Review` WHERE SEseller_id = $seller_id");
    // Finally delete the seller
    mysqli_query($conn, "DELETE FROM Seller WHERE seller_id = $seller_id");
}

// GET all sellers.
$sellers = mysqli_query($conn, "SELECT * FROM Seller");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Sellers</title>
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
        <h2>Manage Sellers</h2>
        <div class="box">
            <table>
                <tr>
                    <th>Seller ID</th>
                    <th>Seller Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($sellers)) { ?>
                <tr>
                    <td><?php echo $row['seller_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['seller_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['seller_email']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="seller_id" value="<?php echo $row['seller_id']; ?>">
                            <button type="submit" name="delete_seller" class="delete-button" onclick="return confirm('Are you sure you want to delete this seller and all their data?');">Delete</button>
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
