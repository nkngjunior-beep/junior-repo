<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Conversion table with fee included
$exchangeRates = [
    "USD" => ["USD" => 1, "EUR" => 0.90, "GBP" => 0.78, "NGN" => 1530, "KES" => 127, "XAF" => 600],
    "EUR" => ["USD" => 1.10, "EUR" => 1, "GBP" => 0.86, "NGN" => 1680, "KES" => 141, "XAF" => 660],
    "GBP" => ["USD" => 1.28, "EUR" => 1.16, "GBP" => 1, "NGN" => 1950, "KES" => 163, "XAF" => 770],
    "NGN" => ["USD" => 0.00065, "EUR" => 0.00060, "GBP" => 0.00051, "NGN" => 1, "KES" => 0.084, "XAF" => 0.39],
    "KES" => ["USD" => 0.0078, "EUR" => 0.0071, "GBP" => 0.0061, "NGN" => 11.9, "KES" => 1, "XAF" => 4.66],
    "XAF" => ["USD" => 0.00166, "EUR" => 0.00153, "GBP" => 0.00130, "NGN" => 2.56, "KES" => 0.2145, "XAF" => 1]
];

$currencies = array_keys($exchangeRates);
$result = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = floatval($_POST['amount']);
    $from = $_POST['from_currency'];
    $to = $_POST['to_currency'];

    if ($from === $to) {
        $converted = $amount;
        $fee = 0;
        $finalAmount = $amount;
    } else {
        $rate = $exchangeRates[$from][$to];
        $converted = round($amount * $rate, 2);
        $fee = round($converted * 0.015, 2); // 1.5% fee
        $finalAmount = $converted + $fee;
    }

    // Get user's latest card
    $res = $conn->query("SELECT * FROM cards WHERE user_id = {$_SESSION['user_id']} ORDER BY card_id DESC LIMIT 1");
    $card = $res->fetch_assoc();

    if ($card) {
        $conn->query("INSERT INTO transactions (card_id, amount, currency, fee) 
                      VALUES ('{$card['card_id']}', '$finalAmount', '$to', '$fee')");

        $result = [
            "from" => $from,
            "to" => $to,
            "converted" => $converted,
            "fee" => $fee,
            "final" => $finalAmount
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Currency Exchange | BTech Virtual Card</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>💱 Currency Exchange Simulation</h2>
    <p>Convert between any two currencies using our current rates + 1.5% service fee.</p>

    <form method="POST" class="form">
        <input type="number" name="amount" placeholder="Enter amount" required min="1" step="0.01">

        <select name="from_currency" required>
            <option disabled selected>Convert FROM</option>
            <?php foreach ($currencies as $cur): ?>
                <option value="<?= $cur ?>"><?= $cur ?></option>
            <?php endforeach; ?>
        </select>

        <select name="to_currency" required>
            <option disabled selected>Convert TO</option>
            <?php foreach ($currencies as $cur): ?>
                <option value="<?= $cur ?>"><?= $cur ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn">Convert</button>
    </form>

    <?php if ($result): ?>
        <div class="shop-summary">
            <h3>🧾 Conversion Summary</h3>
            <p><strong><?= $amount ?> <?= $result['from'] ?></strong> converted to:</p>
            <p><strong><?= $result['converted'] ?> <?= $result['to'] ?></strong></p>
            <p>Transaction Fee (1.5%): <strong><?= $result['fee'] ?> <?= $result['to'] ?></strong></p>
            <p>Total Charged: <strong><?= $result['final'] ?> <?= $result['to'] ?></strong></p>
        </div>
    <?php endif; ?>

    <p class="small-text">
        <a href="dashboard.php" class="btn">← Back to Dashboard</a>
        <a href="logout.php" class="btn">Logout</a>
    </p>
</div>
</body>
</html>

