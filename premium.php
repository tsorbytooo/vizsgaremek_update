<?php
session_start();
require 'database_connect.php';

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];
$msg = "";

// ELŐFIZETÉS AKTIVÁLÁSA
if (isset($_POST['go_premium'])) {
    $update_sql = "UPDATE users SET premium = 1 WHERE id = $user_id";
    if (mysqli_query($conn, $update_sql)) {
        header("Location: dashboard.php?msg=Gratulálunk! Mostantól Premium tag vagy!");
        exit();
    } else {
        $msg = "Hiba történt a tranzakció során.";
    }
}

// JELENLEGI ÁLLAPOT ELLENŐRZÉSE
$u_res = mysqli_query($conn, "SELECT premium FROM users WHERE id = $user_id");
$u_data = mysqli_fetch_assoc($u_res);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalória Center Premium - Szintet léphetsz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card" style="max-width: 550px; margin: 60px auto; border-top: 5px solid #ff9f1c;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #ff9f1c; margin-bottom: 5px;">⭐ Kalória Center PREMIUM</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem;">Vedd át az irányítást az egészséged felett!</p>
    </div>

    <?php if($u_data['premium'] == 1): ?>
        <div style="background: #f0fdf4; border: 1px solid #10b981; padding: 20px; border-radius: 12px; text-align: center;">
            <h3 style="color: #10b981; margin: 0;">Már Premium tag vagy!</h3>
            <p style="margin-bottom: 20px;">Élvezd a korlátlan lehetőségeket.</p>
            
            <div style="display: flex; flex-direction: column; gap: 10px; align-items: center;">
                <a href="dashboard.php" class="btn-primary" style="text-decoration: none; display: inline-block; width: 100%; max-width: 300px;">Vissza a Dashboardra</a>
                
                <a href="cancel_premium.php" onclick="return confirm('Biztosan lemondod a prémium tagságot? Elveszíted a makrók követését!')" style="color: #e71d36; text-decoration: none; font-size: 0.9rem; margin-top: 10px; border: 1px solid #e71d36; padding: 8px 15px; border-radius: 8px;">Prémium előfizetés lemondása</a>
            </div>
        </div>
    <?php else: ?>
        <div class="info-grid" style="grid-template-columns: 1fr; gap: 15px; margin-bottom: 30px;">
            <div class="info-item" style="text-align: left; border-left: 4px solid #ff9f1c;">
                <strong>🚀 Korlátlan naplózás</strong>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin: 5px 0 0;">Nincs napi 3 étel limit. Naplózz annyit, amennyit csak akarsz!</p>
            </div>
            <div class="info-item" style="text-align: left; border-left: 4px solid #4361ee;">
                <strong>📊 Részletes Statisztikák</strong>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin: 5px 0 0;">Lásd a fehérje, szénhidrát és zsír beviteledet is (Makrók).</p>
            </div>
            
        </div>

        <div style="text-align: center;">
            <a href="checkout.php" class="btn-primary" style="background: linear-gradient(45deg, #ff9f1c, #ffbf69); font-size: 1.1rem; padding: 18px; text-decoration: none; display: block; border-radius: 12px; font-weight: bold;">
                Tovább a fizetéshez (4 990 Ft / hó)
            </a>
        </div>
        
        <p style="text-align: center; margin-top: 20px; font-size: 0.85rem; color: var(--text-muted);">
            
        </p>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 30px;">
        <a href="dashboard.php" style="color: var(--primary); text-decoration: none; font-weight: bold;">← Vissza a kezdőoldalra</a>
    </div>
</div>

</body>
</html>