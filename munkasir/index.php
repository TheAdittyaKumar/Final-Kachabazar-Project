<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <style>
    * {
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f7f9fb;
    margin: 0;
    padding: 20px;
    color: #333;
}

.navbar {
    background-color: #007bff;
    padding: 15px 20px;
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.navbar a {
    color: white;
    text-decoration: none;
    margin: 0 12px;
    font-weight: 500;
    padding: 10px 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.navbar a:hover,
.navbar a.active {
    background-color: #0056b3;
}

h1 {
    text-align: center;
    margin-top: 30px;
    font-size: 28px;
    color: #222;
}

.message {
    text-align: center;
    font-weight: bold;
    color: green;
    margin: 20px auto;
}

.error {
    color: red;
}

.product-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding-top: 20px;
    gap: 20px;
}

.product {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    flex-basis: calc(33.33% - 13.33px); /* 3 cards per row with 10% margin */
    max-width: 30%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: transform 0.2s;
}

.product:hover {
    transform: translateY(-5px);
}

.product img {
    width: 100%;
    height: 700px; /* Increase height as needed */
    object-fit: cover;
    border-radius: 6px;
}


.product h2 {
    font-size: 18px;
    margin: 10px 0 5px;
    color: #333;
}

.product p {
    margin: 6px 0;
}

.price {
    font-weight: bold;
    color: #28a745;
    font-size: 16px;
}

.stock {
    font-size: 14px;
    color: #777;
}

.quantity-selector {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 10px 0;
}

.quantity-selector button {
    padding: 6px 10px;
    font-size: 16px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.quantity-selector button:hover {
    background-color: #0056b3;
}

.quantity-selector input {
    width: 50px;
    text-align: center;
    font-size: 16px;
    margin: 0 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background: #f9f9f9;
}

.add-to-cart {
    background-color: #28a745;
    color: white;
    padding: 10px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    width: 100%;
    transition: background-color 0.2s;
}

.add-to-cart:hover {
    background-color: #218838;
}

.add-to-cart:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

@media (max-width: 600px) {
    .product-container {
        flex-direction: column;
        align-items: center;
    }

    .product {
        max-width: 90%;
    }

    .navbar a {
        display: inline-block;
        margin: 8px 6px;
    }
}

</style>

    <div class="navbar">
    <a href="index.php">Product List</a>
    <a href="cart.php">View Cart</a>
</div>

</head>
<body>

<h1>Product List</h1>

<?php if (isset($_GET['success'])): ?>
    <p class="message">Product added to cart successfully!</p>
<?php elseif (isset($_GET['error'])): ?>
    <p class="message error">Error: Not enough stock available.</p>
<?php endif; ?>

<div class="product-container">
    <?php
    include 'config.php';

    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        while($product = $result->fetch_assoc()):
    ?>
        <div class="product">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
            <p class="stock">In Stock: <?php echo $product['stock']; ?></p>

            <form method="POST" action="add_to_cart.php">
                <div class="quantity-selector">
                    <button type="button" onclick="changeQty(this, -1)">−</button>
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" readonly>
                    <button type="button" onclick="changeQty(this, 1)">+</button>
                </div>
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button class="add-to-cart" type="submit" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                    Add to Cart
                </button>
            </form>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>No products found.</p>";
    endif;

    $conn->close();
    ?>
</div>

<script>
function changeQty(btn, change) {
    const input = btn.parentNode.querySelector('input[name="quantity"]');
    let val = parseInt(input.value);
    const max = parseInt(input.max);

    val += change;
    if (val < 1) val = 1;
    if (val > max) val = max;

    input.value = val;
}
</script>

</body>
</html>
