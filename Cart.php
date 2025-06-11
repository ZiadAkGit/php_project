<?php
require_once 'connect_to_DB.php';
require_once 'User.php';

session_start();
$user_id = $_SESSION['id'];

$sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if (isset($_POST['remove_item'])) {
    $product_id = $_POST['remove_item'];
    $delete_sql = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    mysqli_query($conn, $delete_sql);
    header("Location: Cart.php");
}

if (isset($_POST['edit_item']) && isset($_POST['new_quantity'])) {
    $product_id = $_POST['edit_item'];
    $new_quantity = $_POST['new_quantity'];
    $update_sql = "UPDATE cart SET quantity = '$new_quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
    mysqli_query($conn, $update_sql);
    header("Location: Cart.php");
}

if (isset($_POST['submit_order'])) {
    $sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    $products_query = "SELECT * FROM products";
    $products_result = mysqli_query($conn, $products_query);
    $products = mysqli_fetch_assoc($products_result);
    $results = mysqli_fetch_assoc($result);
    if ($products['quantity'] >= $results['quantity']) {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];
            $cart_query = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
            $quantity_query = "UPDATE products SET quantity = quantity - '$quantity' WHERE id = '$product_id'";
            $result_cart = mysqli_query($conn, $cart_query);
            $result_quantity = mysqli_query($conn, $quantity_query);
        }
        if (!$result_cart || !$result_quantity) {
            die("Error processing order: " . mysqli_error($conn));
        } else {
            if ($result_cart["quantity"] <= $result_quantity["quantity"])
                echo "<script>alert('Order placed successfully!');
            window.location.href = 'dashboard.php';
            </script>";
            else {
                echo "<script>alert('Order failed successfully!');
            </script>";
            }
        }
    } else {
        echo "<script>alert('Insufficient stock for one or more products. Please adjust your cart.');
        window.location.href = 'Cart.php';
        </script>";
        $cart_query = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $result_cart = mysqli_query($conn, $cart_query);
        exit();
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Watch Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Shopping Cart</h1>
            <p>Review your selected items</p>
        </div>

        <div class="admin-panel">
            <h2 class="section-title">Cart Items</h2>
            <form action="Cart.php" method="post">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Remove</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $products = "SELECT * FROM products WHERE id = '{$row['product_id']}'";
                                $result_products = mysqli_query($conn, $products);
                                $products_array = mysqli_fetch_assoc($result_products);
                                $subtotal = $products_array['price'] * $row['quantity'];
                                $total += $subtotal;
                                echo "<tr>
                            <td>{$products_array['name']}</td>
                            <td>{$row['quantity']}</td>
                            <td>" . number_format($products_array['price'], 2) . "₪</td>
                            <td>" . number_format($subtotal, 2) . "₪</td>
                            <td><button type='submit' name='remove_item' value='{$row['product_id']}' class='btn btn-danger'>Remove</button></td>
                            <td><button type='button' name='edit_item' onClick='editQuantity({$row['product_id']}, {$row['quantity']})' class='btn btn-warning'>Edit</button></td>
                        </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No items in cart</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div style="margin-top: 20px; padding: 20px; background: #f8f9ff; border-radius: 10px; text-align: center;">
                    <h3 style="color: #333; margin-bottom: 20px;">Total: <?= number_format($total, 2) ?>₪</h3>
                    <button type="submit" name="submit_order" class="btn">Place Order</button>
                    <a href="dashboard.php" class="btn btn-warning">Continue Shopping</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        function editQuantity(productId, currentQty) {
            let newQty = prompt("Enter new quantity:", currentQty);
            if (newQty !== null && !isNaN(newQty) && newQty > 0) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'Cart.php';

                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'edit_item';
                inputId.value = productId;

                const inputQty = document.createElement('input');
                inputQty.type = 'hidden';
                inputQty.name = 'new_quantity';
                inputQty.value = newQty;

                form.appendChild(inputId);
                form.appendChild(inputQty);
                document.body.appendChild(form);
                form.submit();
            } else {
                alert("Invalid quantity.");
            }
        }
    </script>
</body>

</html>