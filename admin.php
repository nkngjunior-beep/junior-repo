<?php
session_start();
include 'db.php';

// Temporary admin access check (optional)
$isAdmin = true; // set to false if you want to secure this later

if (!$isAdmin) {
    die("Access denied.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | BTech Virtual Card</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-table {
            width: 100%;
            margin-top: 25px;
            font-size: 13px;
            border-collapse: collapse;
            color: #fff;
        }

        .admin-table th, .admin-table td {
            padding: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: left;
        }

        .admin-table thead {
            background: rgba(255, 255, 255, 0.2);
        }

        .section-title {
            margin-top: 40px;
            font-size: 20px;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📊 Admin Dashboard</h2>
    <p>Below are full lists of system data.</p>

    <!-- Users -->
    <div class="section-title">👤 All Users</div>
    <table class="admin-table">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th></tr>
        </thead>
        <tbody>
            <?php
            $users = $conn->query("SELECT * FROM users");
            while ($row = $users->fetch_assoc()) {
                echo "<tr><td>{$row['user_id']}</td><td>{$row['name']}</td><td>{$row['email']}</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Cards -->
    <div class="section-title">💳 All Cards</div>
    <table class="admin-table">
        <thead>
            <tr><th>Card ID</th><th>User ID</th><th>Card Number</th><th>CVV</th><th>Expiry</th><th>Balance (XAF)</th></tr>
        </thead>
        <tbody>
            <?php
            $cards = $conn->query("SELECT * FROM cards");
            while ($row = $cards->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['card_id']}</td>
                    <td>{$row['user_id']}</td>
                    <td>{$row['card_number']}</td>
                    <td>{$row['cvv']}</td>
                    <td>{$row['expiry_date']}</td>
                    <td>{$row['balance']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Transactions -->
    <div class="section-title">🧾 All Transactions</div>
    <table class="admin-table">
        <thead>
            <tr><th>Tx ID</th><th>Card ID</th><th>Amount</th><th>Currency</th><th>Fee</th><th>Date</th></tr>
        </thead>
        <tbody>
            <?php
            $tx = $conn->query("SELECT * FROM transactions");
            while ($row = $tx->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['tx_id']}</td>
                    <td>{$row['card_id']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['currency']}</td>
                    <td>{$row['fee']}</td>
                    <td>{$row['tx_date']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <p class="small-text"><a href="dashboard.php" class="btn">← Back to Dashboard</a></p>
</div>
</body>
</html>
