<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = intval($_POST['cart_id']);
    $new_quantity = intval($_POST['quantity']);

    // Get current quantity and product stock
    $stmt = $conn->prepare("
        SELECT c.product_id, c.quantity AS old_quantity, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.id = ?
    ");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->bind_result($product_id, $old_quantity, $stock);
    $stmt->fetch();
    $stmt->close();

    $difference = $new_quantity - $old_quantity;

    if (($stock - $difference) >= 0 && $new_quantity > 0) {
        // Update cart
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_quantity, $cart_id);
        $stmt->execute();
        $stmt->close();

        // Update stock
        $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $difference, $product_id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Not enough stock']);
    }
}
?>
