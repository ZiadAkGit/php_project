<?php
require_once 'connect_to_DB.php';
if (isset($_POST['submit'])) {
    session_start();
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $sql2 = "SELECT isAdmin FROM users WHERE email = '$email'";
    $result2 = mysqli_query($conn, $sql2);
    $isAdmin = mysqli_fetch_assoc($result2)['isAdmin'];
    if (mysqli_num_rows($result) > 0) {
        if ($isAdmin && $password == $user['password']) {
            header("Location: admin_dashboard.php");
        } else if ($password != $user['password']) {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        } else {
            header("Location: dashboard.php");
        }
    } else {
        echo "<script>alert('User not found. Sign Up please!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Watch Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Welcome!</h1>
            <p>Login to access your account</p>
        </div>

        <div class="admin-panel">
            <h2 class="section-title">Login</h2>
            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" name="submit" class="btn">Login</button>
                <a href="signup.php" class="btn btn-warning">Sign Up</a>
            </form>
        </div>
    </div>
</body>

</html>