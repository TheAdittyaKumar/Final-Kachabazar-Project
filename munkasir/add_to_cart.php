<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Get current stock
    $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

    if ($stock >= $quantity) {
        // Check if product is already in cart
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($cart_id, $existing_quantity);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            // Update quantity
            $new_quantity = $existing_quantity + $quantity;
            if ($new_quantity > $stock + $existing_quantity) {
                $stmt->close();
                header("Location: index.php?error=stock");
                exit();
            }

            $stmt->close();
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_quantity, $cart_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt->close();
            // Insert new item into cart
            $stmt = $conn->prepare("INSERT INTO cart (product_id, quantity) VALUES (?, ?)");
            $stmt->bind_param("ii", $product_id, $quantity);
            $stmt->execute();
            $stmt->close();
        }

        // Deduct stock from products table
        $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php?success=1");
        exit();
    } else {
        header("Location: index.php?error=1");
        exit();
    }
}

$conn->close();
?>
