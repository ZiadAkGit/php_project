<?php
require_once 'connect_to_DB.php';
require_once 'User.php';
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = new User($id, $username, $email, $password, 0, 0);
    if ($user->ifExists($conn)) {
        echo "<div class='message error'>Error: User already exists.</div>";
    } else {
        if ($user->saveToDatabase($conn)) {
            echo "<div class='message success'>Success! User has been added to the database.</div>";
        } else {
            echo "<div class='message error'>Error: User could not be added to the database.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Watch Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Join Our Watch Shop</h1>
            <p>Create your account to start shopping</p>
        </div>

        <div class="admin-panel">
            <h2 class="section-title">Sign Up</h2>
            <form action="signup.php" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="id">ID</label>
                        <input type="text" name="id" id="id" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" name="submit" class="btn">Sign Up</button>
                <a href="index.php" class="btn btn-warning">Back to Login</a>
            </form>
        </div>
    </div>
</body>

</html>