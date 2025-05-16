<?php
include 'config.php';

if (isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);

    // First, fetch product_id and quantity from cart
    $sql = "SELECT product_id, quantity FROM cart WHERE id = $cart_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];

        // Add quantity back to stock
        $updateStock = "UPDATE products SET stock = stock + $quantity WHERE id = $product_id";
        $conn->query($updateStock);

        // Delete the cart item
        $deleteCart = "DELETE FROM cart WHERE id = $cart_id";
        if ($conn->query($deleteCart) === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
