<?php
session_start();
require 'database_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Visszaállítjuk a prémiumot 0-ra
$sql = "UPDATE users SET premium = 0 WHERE id = $user_id";

if (mysqli_query($conn, $sql)) {
    // Ha sikerült, visszadobjuk a dashboardra egy üzenettel (opcionális)
    header("Location: dashboard.php?status=premium_cancelled");
} else {
    echo "Hiba történt a lemondás során: " . mysqli_error($conn);
}
exit();