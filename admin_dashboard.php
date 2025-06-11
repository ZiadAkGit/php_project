<?php
require_once 'connect_to_DB.php';
require_once 'User.php';

session_start();

$message = "";
$messageType = "";

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
            $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

            $query = "INSERT INTO products (name, price, quantity, image_url) VALUES ('$name', '$price', '$quantity', '$image_url')";

            if (mysqli_query($conn, $query)) {
                $message = "Product added successfully";
                $messageType = "success";
            } else {
                $message = "Error adding product: " . mysqli_error($conn);
                $messageType = "error";
            }
            break;

        case 'edit':
            $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
            $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

            $query = "UPDATE products SET name='$name', price='$price', quantity='$quantity', image_url='$image_url' WHERE id='$product_id'";

            if (mysqli_query($conn, $query)) {
                $message = "Product updated successfully";
                $messageType = "success";
            } else {
                $message = "Error updating product: " . mysqli_error($conn);
                $messageType = "error";
            }
            break;

        case 'delete':
            $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
            $query = "DELETE FROM products WHERE id='$product_id'";

            if (mysqli_query($conn, $query)) {
                $message = "Product deleted successfully";
                $messageType = "success";
            } else {
                $message = "Error deleting product: " . mysqli_error($conn);
                $messageType = "error";
            }
            break;
    }
}

$result = mysqli_query($conn, "SELECT * FROM products ORDER BY price DESC");
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🛠️ Admin Dashboard</h1>
            <br
                <p><a href="index.php" class="logout-btn">Logout</a></p>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="admin-panel">
            <h2 class="section-title">➕ Add New Product</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Product Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="image_url">Image URL:</label>
                        <input type="text" id="image_url" name="image_url" required>
                    </div>
                </div>

                <button type="submit" class="btn">Add Product</button>
            </form>
        </div>


        <div class="admin-panel">
            <h2 class="section-title">📦 Product List</h2>

            <?php if (empty($products)): ?>
                <p style="text-align: center; color: #666; font-size: 18px; padding: 40px;">
                    No products found. Please add some products using the form above.
                </p>
            <?php else: ?>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['price']; ?>₪</td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning" onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                        Edit
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                                <td>
                                    <?php if ($product['image_url']): ?>
                                        <img class="img" src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                                    <?php else: ?>
                                        <span>No Image</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2 style="margin-bottom: 20px; color: #333;">✏️ Edit Product</h2>

            <form method="POST" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_product_id" name="product_id">

                <div class="form-group">
                    <label for="edit_name">Product Name:</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_price">Price:</label>
                        <input type="number" id="edit_price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_quantity">Quantity:</label>
                        <input type="number" id="edit_quantity" name="quantity" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_image_url">Image URL:</label>
                    <input type="text" id="edit_image_url" name="image_url" required>
                </div>
                <button type="submit" class="btn">Update Product</button>
                <button type="button" class="btn btn-danger" onclick="closeEditModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function editProduct(product) {
            document.getElementById('edit_product_id').value = product.id;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_quantity').value = product.quantity;
            document.getElementById('edit_image_url').value = product.image_url;

            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.05)';
                });

                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Animate table rows on load
            const rows = document.querySelectorAll('.products-table tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>