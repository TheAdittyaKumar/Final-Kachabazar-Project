<?php
include 'config.php';

$sql = "DELETE FROM cart";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not clear cart.']);
}

$conn->close();
?>
