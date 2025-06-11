<?php
require_once "connect_to_DB.php";
require_once "User.php";

session_start();
$user_id = $_SESSION['id'];
$username = $_SESSION['username'];

$sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
$sql_products = "SELECT * FROM products";
$result_products = mysqli_query($conn, $sql_products);
if (!$result_products) {
    die("Error fetching products: " . mysqli_error($conn));
}
$result = mysqli_query($conn, $sql);
$cartCount = mysqli_num_rows($result);

if (isset($_POST['add_to_cart'])) {
    $quantities = $_POST['quantities'];
    $flag = 1;
    foreach ($quantities as $product_id => $quantity) {
        $products = "SELECT * FROM products WHERE id = $product_id";
        $products_result = mysqli_query($conn, $products);
        $product = mysqli_fetch_assoc($products_result);
        if ($product['quantity'] < $quantity) {
            $flag = 0;
            echo "<script>alert('Insufficient stock for product: {$product['name']}');</script>";
            break;
        } else {
            if ($quantity > 0) {
                $insert_into_cart = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
                $result = mysqli_query($conn, $insert_into_cart);
                if (!$result) {
                    die("Error adding product to cart: " . mysqli_error($conn));
                }
            }
        }
    }
    if (count($quantities) <= 0 && $flag == 1) {
        echo "<script>alert('Please select at least one product to add to the cart.');</script>";
    } else if (count($quantities) > 0 && $flag == 1) {
        echo "<script>alert('Products added to cart successfully!');
    window.location.href = 'Cart.php';</script>";
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Watch Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <?php
            echo "<h1>Welcome ${username}</h1>";
            echo "<p>Here you can buy our finest watches!</p>";
            ?>

            <br>
            <br>

            <a href="index.php" class="logout-btn">Logout</a>


        </div>

        <div class="admin-panel">
            <h2 class="section-title">Project Details</h2>
            <div class="form-row">
                <div class="form-group">
                    <label><b>Developers:</b></label>
                    <p>Nour Nimry - 208527762</p>
                    <p>Ziad Abu Khadra - 206028573</p>
                </div>
                <div class="form-group">
                    <label><b>Project Description:</b></label>
                    <p>An online store for selling handmade watches with a complete management system</p>
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <h2 class="section-title">Products</h2>
            <form method="post">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>In Stock</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_products) > 0) {
                            while ($product = mysqli_fetch_assoc($result_products)) {
                                $product_id = $product['id'];
                                echo "<tr>
                                    <td><img class='img' src='{$product['image_url']}' alt='{$product['name']}'><br><b>{$product['name']}</b></td>
                                    <td><b>" . number_format($product['price'], 2) . "₪</b></td>
                                    <td><b>{$product['quantity']}</b></td>
                                    <td>
                                    <button type='button' onclick='addQuantity($product_id)' class='btn' style='padding: 5px 10px; margin-left: 5px;'>+</button>
                                        <input type='text' name='quantities[$product_id]' id='qty-$product_id' value='0' style='width: 65px; text-align: center; padding: 5px; border: 2px solid #ddd; border-radius: 5px;' />
                                        <button type='button' onclick='subtractQuantity($product_id)' class='btn' style='padding: 5px 10px; margin-left: 5px;'>-</button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No products found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div style="margin-top: 20px; text-align: center;">
                    <button type='submit' name='add_to_cart' class='btn'>Add Selected to Cart</button>
                    <a href="Cart.php" class="btn btn-warning">Shopping Cart (<?php echo $cartCount; ?>)</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addQuantity(productId) {
            const input = document.getElementById('qty-' + productId);
            input.value = parseInt(input.value) + 1;
        }

        function subtractQuantity(productId) {
            const input = document.getElementById('qty-' + productId);
            input.value = Math.max(0, parseInt(input.value) - 1);
        }
    </script>
</body>

</html>