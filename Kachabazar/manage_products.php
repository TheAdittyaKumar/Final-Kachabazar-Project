<?php
session_start();

if (!isset($_SESSION['seller_id']) || $_SESSION['role'] != 'seller') {
    header("Location: login.php");
    exit(); //This ensures only sellers can access this page
}
$conn = mysqli_connect('localhost', 'root', '', 'kachabazarDB'); //connecting database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//new product adding is done here. 
if (isset($_POST['add_product'])) {
    $groc_name = $_POST['groc_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $groc_quantity = $_POST['groc_quantity'];
    $seller_id = $_SESSION['seller_id'];
    //This helps us to insert new product into Grocery_items table. SEseller_id is helping us link to who actually owns the product.
    $insert_query = "INSERT INTO Grocery_items (Groc_name, category, description, price, Groc_quantity, SEseller_id) VALUES ('$groc_name', '$category', '$description', '$price', '$groc_quantity', '$seller_id')";
    mysqli_query($conn, $insert_query);
}

if (isset($_POST['update_stock'])) { //update stock using this code
    $item_id = $_POST['item_id'];
    $add_quantity = $_POST['add_quantity']; 
    //add new stock using this
    $update_query = "UPDATE Grocery_items SET Groc_quantity = Groc_quantity + $add_quantity WHERE item_id = $item_id AND SEseller_id = {$_SESSION['seller_id']}";
    mysqli_query($conn, $update_query);
}

if (isset($_POST['reduce_stock'])) { //This handles the stock reduction code
    $item_id = $_POST['item_id'];
    $reduce_quantity = $_POST['reduce_quantity'];
    //reduce the product quantity but remember negative isnt allowed
    $update_query = "UPDATE Grocery_items SET Groc_quantity = CASE WHEN Groc_quantity >= $reduce_quantity THEN Groc_quantity - $reduce_quantity ELSE 0 END WHERE item_id = $item_id AND SEseller_id = {$_SESSION['seller_id']}";
    mysqli_query($conn, $update_query);
}

if (isset($_POST['delete_product'])) { //This takes care of deleting the products
    $item_id = $_POST['item_id'];
    // Delete product where item_id matches and if the logged in seller owns it
    $delete_query = "DELETE FROM Grocery_items WHERE item_id = $item_id AND SEseller_id = {$_SESSION['seller_id']}";
    mysqli_query($conn, $delete_query);
}
//Get all products of this seller 
$seller_id = $_SESSION['seller_id'];
$product_query = "SELECT * FROM Grocery_items WHERE SEseller_id = $seller_id";
$product_result = mysqli_query($conn, $product_query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="css/styles.css">

</head>
<body class="manage-products-page">
<div class="manage-products-header">   
<h2>Welcome, <?php echo $_SESSION['seller_name']; ?>! Manage Your Products Below:</h2>
</div>
<div class="add-product-box">
    <h3>Add New Product</h3>
    <form method="POST" action="">
        <label>Product Name:</label>
        <input type="text" name="groc_name" autocomplete="off" required>

        <label>Category:</label>
        <select name="category" required>
            <option value="">--Select Category--</option>
            <option value="Fruits">Fruits</option>
            <option value="Vegetables">Vegetables</option>
            <option value="Snacks">Snacks</option>
            <option value="Dairy">Dairy</option>
            <option value="Bakery">Bakery</option>
            <option value="Meat">Meat</option>
            <option value="Seafood">Seafood</option>
            <option value="Beverages">Beverages</option>
        </select>

        <label>Description:</label>
        <textarea name="description" rows="3" cols="30" autocomplete="off"></textarea>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" required>

        <label>Quantity:</label>
        <input type="number" name="groc_quantity" required>

        <input type="submit" name="add_product" value="Add Product">
    </form>
</div>

<hr>
<h3>Your Products</h3> <!-- Shows us the products we have -->
<table border="1" cellpadding="10">
    <tr>
        <th>Product ID</th>
        <th>Grocery Name</th>
        <th>Category</th>
        <th>Description</th>
        <th>Price</th>
        <th>Quantity Available</th>
        <th>Manage Stock</th>
        <th>Delete Product</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($product_result)) { ?>
        <tr>
            <td><?php echo $row['item_id']; ?></td>
            <td><?php echo $row['Groc_name']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['Groc_quantity']; ?></td>
            <td>
                <form method="POST" action="">  <!-- This will help the seller to add or reduce Stock -->
                    <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                    <input type="number" name="add_quantity" min="1" placeholder="Add" required>
                    <input type="submit" name="update_stock" value="Add Stock">
                </form>
                <form method="POST" action="" style="margin-top:5px;">
                    <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                    <input type="number" name="reduce_quantity" min="1" placeholder="Reduce" required>
                    <input type="submit" name="reduce_stock" value="Reduce Stock">
                </form>
            </td>
            <td>      <!-- This help the seller to delete Products -->
                <form method="POST" action="">
                    <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                    <input type="submit" name="delete_product" value="Delete" onclick="return confirm('Are you sure you want to delete this product?');">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
<br><br>
<div class="manage-buttons">
<a href="seller_dashboard.php">Go back to Dashboard</a> |
<a href="logout.php">Logout</a>
</div>
</body>
</html>
