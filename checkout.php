<?php
session_start();
require 'database_connect.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];

if (isset($_POST['complete_payment'])) {
    // Itt szimuláljuk a fizetést - a valóságban itt menne a banki ellenőrzés
    $update_sql = "UPDATE users SET premium = 1 WHERE id = $user_id";
    if (mysqli_query($conn, $update_sql)) {
        header("Location: dashboard.php?msg=Sikeres fizetés! Üdvözlünk a Prémium tagok között!");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Fizetés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #f4f7f6;">

<div class="card" style="max-width: 500px; margin: 50px auto; padding: 30px;">
    <h2 style="text-align: center; color: #2b2d42;">💳 Biztonságos Fizetés</h2>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 25px;">Kalória Center Premium Előfizetés</p>

    <form method="POST">
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Kártyabirtokos neve</label>
            <input type="text" name="card_name" placeholder="Minta János" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Kártyaszám</label>
            <input type="text" name="card_number" placeholder="1234 5678 1234 5678" maxlength="19" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Lejárat</label>
                <input type="text" name="exp" placeholder="MM/YY" maxlength="5" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">CVC</label>
                <input type="text" name="cvc" placeholder="123" maxlength="3" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Összesen fizetendő:</span>
                <strong>4 990 Ft</strong>
            </div>
            <small style="color: var(--text-muted); font-size: 0.8rem;">Az előfizetés havonta automatikusan megújul.</small>
        </div>

        <button type="submit" name="complete_payment" class="btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem; background: #10b981;">
            Fizetés Bejezése
        </button>
        
        <a href="premium.php" style="display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; font-size: 0.9rem;">Mégse</a>
    </form>
    
    <div style="text-align: center; margin-top: 20px;">
        <small style="color: #ccc;">🔒 SSL titkosított, biztonságos kapcsolat</small>
    </div>
</div>

</body>
</html>