<?php
session_start();
include("config.php");

//This helps us to handle "Add to Cart" functionality
if (isset($_POST['add_to_cart'])) {
    $item_id = intval($_POST['item_id']);
    $item_name = $_POST['item_name'];
    $price = floatval($_POST['price']);
    $seller_id = intval($_POST['seller_id']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['item_id'] == $item_id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'item_id' => $item_id,
            'name' => $item_name,
            'price' => $price,
            'quantity' => 1,
            'seller_id' => $seller_id
        ];
    }
}

//This helps us to handle remove from cart
if (isset($_POST['remove'])) {
    $index = intval($_POST['remove_index']);
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

//This helps us to handle order now
if (isset($_POST['place_order']) && !empty($_SESSION['cart'])) {
    $admin_customer_id = 1; // Replace with admin's actual user ID from user table

    //Insert the order into the table order
    $stmt = $conn->prepare("INSERT INTO `order` (ORdate_time, total_bill, ORpayment_status, UScustomer_id) VALUES (NOW(), 0, 'Pending', ?)");
    $stmt->bind_param("i", $admin_customer_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();
    $total_bill = 0;
    $stmt_contain = $conn->prepare("INSERT INTO contain (GRitem_id, ORorder_id, quantity) VALUES (?, ?, ?)");
    $stmt_inventory = $conn->prepare("UPDATE grocery_items SET Groc_quantity = Groc_quantity - ? WHERE item_id = ?");
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total_bill += $subtotal;
        $stmt_contain->bind_param("iii", $item['item_id'], $order_id, $item['quantity']); //contain inserter
        $stmt_contain->execute();
        $stmt_inventory->bind_param("ii", $item['quantity'], $item['item_id']); //stock updater
        $stmt_inventory->execute();
    }
    $stmt_contain->close();
    $stmt_inventory->close();
    // Update total bill in order
    $stmt_update = $conn->prepare("UPDATE `order` SET total_bill = ? WHERE order_id = ?");
    $stmt_update->bind_param("di", $total_bill, $order_id);
    $stmt_update->execute();
    $stmt_update->close();
    // Clear cart
    unset($_SESSION['cart']);
    echo "<script>alert('Order placed successfully!'); window.location='admin_browse_sellers.php';</script>";
    exit;
}

// Get all of the items
$sql = "SELECT gi.item_id, gi.Groc_name, gi.price, gi.Groc_quantity, s.store_name, s.seller_id FROM grocery_items gi JOIN seller s ON gi.SEseller_id = s.seller_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Browse Sellers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        .btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-remove {
            background-color: #dc3545;
        }
        .btn-remove:hover {
            background-color: #c82333;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #222;
        }
    </style>
</head>
<body>
    <h2>Browse All Sellers & Products</h2>

    <table>
        <tr>
            <th>Item</th>
            <th>Store</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Add to Cart</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['Groc_name']) ?></td>
            <td><?= htmlspecialchars($row['store_name']) ?></td>
            <td><?= number_format($row['price'], 2) ?> Tk</td>
            <td><?= $row['Groc_quantity'] ?></td>
            <td>
                <form method="post" style="margin:0;">
                    <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>">
                    <input type="hidden" name="item_name" value="<?= htmlspecialchars($row['Groc_name']) ?>">
                    <input type="hidden" name="price" value="<?= $row['price'] ?>">
                    <input type="hidden" name="seller_id" value="<?= $row['seller_id'] ?>">
                    <input type="submit" name="add_to_cart" value="Add" class="btn">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h3>Your Cart</h3>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $index => $cart_item):
                $subtotal = $cart_item['price'] * $cart_item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($cart_item['name']) ?></td>
                <td><?= $cart_item['quantity'] ?></td>
                <td><?= number_format($cart_item['price'], 2) ?> Tk</td>
                <td><?= number_format($subtotal, 2) ?> Tk</td>
                <td>
                    <form method="post" style="margin:0;">
                        <input type="hidden" name="remove_index" value="<?= $index ?>">
                        <input type="submit" name="remove" value="Remove" class="btn btn-remove">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="total">Grand Total: <?= number_format($total, 2) ?> Tk</div>

        <form method="post">
            <input type="submit" name="place_order" value="Order Now" class="btn" style="margin-top:10px;">
        </form>
        

    <?php endif; ?>
    <div>
            <a href="admin_dashboard.php"> Go back to Dashboard</a>
    </div>
</body>
</html>





