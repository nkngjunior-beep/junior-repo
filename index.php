<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Virtual Card Banking</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="Bank Logo" class="logo" />
        <h2>Welcome to BTech Virtual Card System</h2>
        <p>Secure. Smart. Instant Virtual Banking.</p>

        <div class="button-group">
            <a href="register.php" class="btn">Register</a>
            <a href="login.php" class="btn">Login</a>
        </div>

        <div class="separator">OR</div>

        <a href="#" class="google-btn">
            <img src="https://e7.pngegg.com/pngimages/882/225/png-clipart-google-logo-google-logo-google-search-icon-google-text-logo-thumbnail.png" alt="Google Logo">
            Sign in with Google
        </a>
    </div>
</body>
</html>