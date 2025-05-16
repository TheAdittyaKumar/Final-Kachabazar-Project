<?php
include 'config.php'; // Include your database connection file
// Include your database connection file    

// Fetch products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Output as array for HTML
$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();

// Make available to HTML
header('Content-Type: application/json');
echo json_encode($products);
?>
