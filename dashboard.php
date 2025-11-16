<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Card generator functions
function generateCardNumber() {
    $prefix = "4222";
    for ($i = 0; $i < 11; $i++) {
        $prefix .= rand(0, 9);
    }
    return $prefix;
}

function generateCVV() {
    return str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
}

function generateExpiry() {
    $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
    $year = date("Y") + rand(1, 3);
    return "$month/$year";
}

$user_id = $_SESSION['user_id'];
$card = null;
$message = "";

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generate'])) {
        $card_number = generateCardNumber();
        $cvv = generateCVV();
        $expiry = generateExpiry();
        $conn->query("INSERT INTO cards (user_id, card_number, cvv, expiry_date, balance) 
                      VALUES ('$user_id', '$card_number', '$cvv', '$expiry', 0.00)");
        $message = "Card generated.";
    }

    if (isset($_POST['delete'])) {
        $conn->query("DELETE FROM cards WHERE user_id = $user_id");
        $message = "Card deleted.";
    }

    if (isset($_POST['custom_load']) && is_numeric($_POST['amount'])) {
        $amount = floatval($_POST['amount']);
        $conn->query("UPDATE cards SET balance = balance + $amount WHERE user_id = $user_id");
        $message = "$$amount loaded successfully.";
    }
}

// Fetch latest card
$res = $conn->query("SELECT * FROM cards WHERE user_id = $user_id ORDER BY card_id DESC LIMIT 1");
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $card = [
        'number' => $row['card_number'],
        'cvv' => $row['cvv'],
        'expiry' => $row['expiry_date'],
        'balance' => $row['balance']
    ];
}
?>

<?php include 'header.php'; ?>
<div class="container" style="text-align: center; padding: 40px;">
    <h2>Hello, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h2>
    <p>Welcome to your virtual card dashboard.</p>

    <!-- ACTION BUTTONS -->
    <form method="POST" style="margin: 30px 0; display: flex; flex-wrap: wrap; justify-content: center; gap: 15px;">
        <button type="submit" name="generate" class="glass-btn"> Generate Card</button>
        <button type="button" class="glass-btn" onclick="document.getElementById('loadModal').style.display='block'">Load Custom Amount</button>
        <button type="submit" name="delete" class="glass-btn" onclick="return confirm('Delete your card?')">Delete Card</button>
        <a href="transactions.php" class="glass-btn"> Transactions</a>
        <a href="home.php" class="glass-btn"> Home</a>
    </form>

    <?php if (!empty($message)): ?>
        <div style="color: #00ffcc; font-weight: bold;"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($card): ?>
    <!-- CARD DISPLAY -->
    <div class="card-wrapper" style="margin-top: 40px; display: flex; justify-content: center;">
        <div style="
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            width: 480px;
            padding: 30px;
            color: #fff;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            text-align: left;
        ">
            <div style="display: flex; justify-content: space-between;">
                <span><strong>💠 Visa Prepaid</strong></span>
                <span>💳</span>
            </div>
            <div style="font-size: 1.6rem; margin: 20px 0; letter-spacing: 2px;">
                <?= chunk_split($card['number'], 4, ' ') ?>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                <div><small>GOOD THRU</small><div><?= $card['expiry'] ?></div></div>
                <div><small>CARD HOLDER</small><div><?= strtoupper($_SESSION['name']) ?></div></div>
                <div style="font-weight: bold;">VISA</div>
            </div>
            <div style="margin-top: 15px;">
                <small>CVV:</small>
                <input type="password" value="<?= $card['cvv'] ?>" id="cvvInput" readonly style="background: transparent; color: #fff; border: none; font-weight: bold; font-size: 1rem;">
                <button type="button" onclick="toggleCVV()" style="background: none; border: none; color: #00ffff;">👁</button>
            </div>
            <div style="margin-top: 20px; font-size: 1.2rem; font-weight: bold; color: #00ffcc;">
                💰 Balance: $<?= number_format($card['balance'], 2) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- GLASS BUTTON STYLES -->
<style>
.glass-btn {
    padding: 12px 20px;
    border: 1px solid rgba(255,255,255,0.4);
    background: rgba(255,255,255,0.15);
    color: #fff;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s ease;
    cursor: pointer;
    backdrop-filter: blur(8px);
}
.glass-btn:hover {
    background: rgba(255,255,255,0.3);
    color: #000;
}
</style>

<!-- MODAL FOR CUSTOM LOAD -->
<div id="loadModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7);">
    <div style="background:#fff; padding:20px; max-width:400px; margin:100px auto; border-radius:12px; color:#000; text-align:center;">
        <h3>Enter Amount to Load</h3>
        <form method="POST">
            <input type="number" name="amount" min="1" placeholder="e.g. 200" style="padding:10px; width:80%; margin-bottom:10px;" required>
            <br>
            <button type="submit" name="custom_load" style="padding:8px 20px;">Load</button>
            <button type="button" onclick="document.getElementById('loadModal').style.display='none'" style="padding:8px 20px;">Cancel</button>
        </form>
    </div>
</div>

<!-- TOGGLE CVV SCRIPT -->
<script>
function toggleCVV() {
    var input = document.getElementById("cvvInput");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

<?php include 'footer.php'; ?>
