<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
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

    table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    margin-bottom: 20px;
    border-radius: 8px;
    overflow: hidden;
    table-layout: fixed;
}

th, td {
    padding: 14px 10px;
    border: 1px solid #e0e0e0;
    text-align: center;
    vertical-align: middle;
    font-size: 16px;
    word-wrap: break-word;
}

th {
    background-color: #0080ff;
    color: white;
    font-weight: bold;
}

.qty-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.qty-buttons button {
    padding: 6px 12px;
    font-size: 16px;
    background-color: #0080ff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.qty-buttons button:hover {
    background-color: #006bd6;
}

.qty {
    font-weight: bold;
    font-size: 16px;
    display: inline-block;
    min-width: 24px;
    text-align: center;
}

.subtotal {
    color: green;
    font-weight: bold;
}

.total {
    font-size: 18px;
    text-align: right;
    font-weight: bold;
    margin-top: 20px;
}

.cart-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
    border-top: 2px solid #ddd;
    margin-top: 20px;
    font-size: 18px;
}

.checkout-btn {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.checkout-btn:hover {
    background-color: #218838;
}

.thank-you-message {
    margin-top: 20px;
    padding: 15px;
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
}


    /* Responsive table */
    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        th {
            display: none;
        }

        td {
            position: relative;
            padding-left: 50%;
            border: none;
            border-bottom: 1px solid #ddd;
        }

        td:before {
            position: absolute;
            left: 15px;
            top: 15px;
            font-weight: bold;
            color: #555;
        }

        td:nth-of-type(1):before { content: "Product"; }
        td:nth-of-type(2):before { content: "Price"; }
        td:nth-of-type(3):before { content: "Quantity"; }
        td:nth-of-type(4):before { content: "Total"; }
    }
</style>

</head>
<body>

<div class="navbar">
    <a href="index.php">Product List</a>
    <a href="cart.php">View Cart</a>
</div>

<h1>Your Cart</h1>

<table id="cart-table">
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>

    <?php
    include 'config.php';
    $total = 0;

    $sql = "SELECT c.id AS cart_id, c.quantity, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            $subtotal = $row['price'] * $row['quantity'];
            $total += $subtotal;
    ?>
    <tr data-cart-id="<?php echo $row['cart_id']; ?>">
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td>$<?php echo number_format($row['price'], 2); ?></td>
        <td>
            <div class="qty-buttons">
                <button onclick="updateQty(this, -1)">−</button>
                <span class="qty"><?php echo $row['quantity']; ?></span>
                <button onclick="updateQty(this, 1)">+</button>
            </div>
        </td>
        <td class="subtotal">$<?php echo number_format($subtotal, 2); ?></td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="4">Cart is empty.</td></tr>
    <?php endif; $conn->close(); ?>
</table>
<div class="cart-summary">
    <div class="total">Total: $<span id="grand-total"><?php echo number_format($total, 2); ?></span></div>
    <button class="checkout-btn" onclick="checkout()">Proceed to Checkout</button>
</div>

<div id="thank-you" class="thank-you-message" style="display: none;">
    🎉 Thank you for your purchase!
</div>


<script>
function updateQty(button, delta) {
    const row = button.closest('tr');
    const qtySpan = row.querySelector('.qty');
    const cartId = row.getAttribute('data-cart-id');
    const productName = row.children[0].textContent;
    let qty = parseInt(qtySpan.textContent);

    if (qty + delta === 0) {
        if (confirm(`Do you want to remove "${productName}" from the cart?`)) {
            // Call remove function
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `cart_id=${cartId}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    row.remove();
                    updateTotal();
                } else {
                    alert(data.message || 'Failed to remove item');
                }
            });
        } else {
            // Reset quantity to 1 visually (no backend call)
            qtySpan.textContent = 1;
        }
        return;
    }

    qty += delta;

    fetch('update_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `cart_id=${cartId}&quantity=${qty}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            qtySpan.textContent = qty;
            const price = parseFloat(row.children[1].textContent.replace('$', ''));
            const subtotal = price * qty;
            row.querySelector('.subtotal').textContent = '$' + subtotal.toFixed(2);
            updateTotal();
        } else {
            alert(data.message || 'Failed to update cart');
        }
    });
}


function updateTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.subtotal').forEach(cell => {
        grandTotal += parseFloat(cell.textContent.replace('$', ''));
    });
    document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
}

function checkout() {
    fetch('clear_cart.php', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-table').innerHTML = `
                <tr><td colspan="4">Cart is empty.</td></tr>`;
            document.getElementById('grand-total').textContent = '0.00';
            document.getElementById('thank-you').style.display = 'block';
        } else {
            alert(data.message || 'Failed to clear cart');
        }
    });
}


</script>

</body>
</html>
