<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['name'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BTech Virtual Card</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: url('https://img.freepik.com/free-vector/bank-card-coin-withdrawal-money-money-payment-symbol-vector-illustration_587448-912.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      overflow-x: hidden;
    }

    header {
      background: rgba(0, 42, 128, 0.85);
      backdrop-filter: blur(10px);
      padding: 20px 30px;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      width: 80%;
      margin: 0 auto;
    }

    .logo {
      font-weight: bold;
      font-size: 22px;
      display: flex;
      align-items: center;
    }

    .logo img {
      width: 250px;
      height: 40px;
      margin-right: 10px;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      margin-left: 20px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    nav a:hover {
      text-decoration: underline;
    }

    nav form {
      display: inline;
    }

    .btn {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      padding: 8px 16px;
      font-weight: 600;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      margin-left: 20px;
      transition: all 0.3s ease;
    }

    .btn:hover {
      background: rgba(255, 255, 255, 0.4);
      color: #000;
    }

    .container {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 40px 30px;
      max-width: 1200px;
      width: 100%;
      margin: 40px auto;
      color: #fff;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>
<body>

<header>
  <div class="header-content">
    <div class="logo">
      <img src="logo.png" alt="Logo">
    </div>
    <nav>
      <a href="home.php">➕ Home</a>
      <?php if ($isLoggedIn): ?>
        <a href="dashboard.php">➕ Dashboard</a>
        <a href="transactions.php">➕ Transactions</a>
        <a href="shop.php">➕ Convert Currency</a>
        <form action="logout.php" method="POST">
          <button type="submit" class="btn">Logout</button>
        </form>
      <?php else: ?>
        <a href="login.php">➕ Login</a>
        <a href="register.php">➕ Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

