<?php
session_start();
include 'db.php';
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Virtual Card Banking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
         <img src="logo.png" alt="Bank Logo" class="logo" />
        <h2>Login to Your Account</h2>
        <p>Access your virtual card dashboard</p>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="form">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Your Password" required>
            <button type="submit" class="btn">Login</button>
        </form>

        <p class="small-text">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
