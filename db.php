<?php
$host = "localhost";
$db = "u289482701_virtualcarddb"; // Your actual database name
$user = "u289482701_vc_user";     // ✅ Fixed closing quote
$pass = '@@##AAbb679926';         // Your MySQL password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

