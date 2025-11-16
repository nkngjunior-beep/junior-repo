<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$query = "
    SELECT t.tx_id, t.amount, t.currency, t.fee, t.tx_date 
    FROM transactions t 
    JOIN cards c ON t.card_id = c.card_id 
    WHERE c.user_id = {$_SESSION['user_id']} 
    ORDER BY t.tx_date DESC
";

$transactions = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>💼 Transaction History</h2>
    <p>Below are your latest simulated transactions.</p>

    <?php if ($transactions->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Fee</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while ($row = $transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $row['amount'] ?></td>
                        <td><?= $row['currency'] ?></td>
                        <td><?= $row['fee'] ?></td>
                        <td><?= date("d M Y, H:i", strtotime($row['tx_date'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No transactions found.</p>
    <?php endif; ?>

    <p class="small-text">
        <a href="dashboard.php" class="btn">← Back to Dashboard</a>
        <a href="logout.php" class="btn">Logout</a>
    </p>
</div>
</body>
</html>
