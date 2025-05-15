<?php
session_start();
include("config.php");

//Checks if admin is logged in or not
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
// Adding to cart here
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $_SESSION['cart'][$item_id] = $quantity;
}

//Removing from cart
if (isset($_POST['remove_item'])) {
    $item_id = $_POST['item_id'];
    unset($_SESSION['cart'][$item_id]);
}

// Placing the order
if (isset($_POST['place_order']) && !empty($_SESSION['cart'])) {
    $admin_customer_id = $_SESSION['admin_id']; // Admin behaves like customer
    $conn->query("INSERT INTO `order` (ORdate_time, total_bill, ORpayment_status, UScustomer_id) VALUES (NOW(), 0, 'Pending', $admin_customer_id)");
    $order_id = $conn->insert_id;
    $total = 0;

    foreach ($_SESSION['cart'] as $item_id => $qty) {
        $item_res = $conn->query("SELECT price FROM grocery_items WHERE item_id = $item_id");
        $item = $item_res->fetch_assoc();
        $price = $item['price'];
        $total += $price * $qty;

        $conn->query("INSERT INTO contain (GRitem_id, ORorder_id, quantity) VALUES ($item_id, $order_id, $qty)");
        $conn->query("UPDATE grocery_items SET Groc_quantity = Groc_quantity - $qty WHERE item_id = $item_id");
    }

    $conn->query("UPDATE `order` SET total_bill = $total WHERE order_id = $order_id");
    unset($_SESSION['cart']); // Clear cart
    header("Location: admin_order_items.php?success=1");
    exit();
}

// Get all sellers and their items using this
$sellers = $conn->query("SELECT s.seller_id, s.store_name, g.* FROM seller s JOIN grocery_items g ON s.seller_id = g.SEseller_id ORDER BY s.store_name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Order Items</title>
    <style>
        body { font-family: Arial; margin: 20px; background-color: #f7f7f7; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #eee; }
        .btn { padding: 5px 10px; border: none; background: #007bff; color: white; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .remove-btn { background: #dc3545; }
        .remove-btn:hover { background: #c82333; }
    </style>
</head>
<body>
    <h2>Order Items from Sellers (As Admin)</h2>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">✅ Order placed successfully!</p>
    <?php endif; ?>

    <form method="post">
        <table>
            <tr>
                <th>Store</th><th>Item</th><th>Price</th><th>Available</th><th>Qty</th><th>Action</th>
            </tr>
            <?php while ($row = $sellers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['store_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Groc_name']); ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['Groc_quantity']; ?></td>
                    <td>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['Groc_quantity']; ?>">
                        <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                    </td>
                    <td><button type="submit" name="add_to_cart" class="btn">Add</button></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </form>

    <h3>🛒 Your Cart</h3>
    <form method="post">
        <table>
            <tr><th>Item ID</th><th>Quantity</th><th>Action</th></tr>
            <?php if (!empty($_SESSION['cart'])): ?>
                <?php foreach ($_SESSION['cart'] as $id => $qty): ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $qty; ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?php echo $id; ?>">
                                <button name="remove_item" class="btn remove-btn">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">Your cart is empty.</td></tr>
            <?php endif; ?>
        </table>
        <?php if (!empty($_SESSION['cart'])): ?>
            <br><button type="submit" name="place_order" class="btn">Order Now</button>
        <?php endif; ?>
    </form>
</body>
</html>

