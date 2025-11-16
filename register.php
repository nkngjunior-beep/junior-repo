<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';
include 'db.php';
$success = $error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = "This email is already registered.";
    } else {
        $insert = $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        if ($insert) {
            $success = "Registration successful. <a href='login.php'>Login here</a>.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Virtual Card Banking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="Bank Logo" class="logo" />
        <h2>Create Your Account</h2>
        <p>Join our virtual banking experience today.</p>

        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="form">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit" class="btn">Register</button>
        </form>

        <p class="small-text">Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
