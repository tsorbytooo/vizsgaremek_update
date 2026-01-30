<?php
session_start();
require 'database_connect.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$msg = "";

// ADATOK MÓDOSÍTÁSA
if (isset($_POST['update_profile'])) {
    $weight = (int)$_POST['weight'];
    $height = (int)$_POST['height'];
    $age = (int)$_POST['age'];
    
    $sql = "UPDATE users SET weight=$weight, height=$height, age=$age WHERE id=$user_id";
    if (mysqli_query($conn, $sql)) { $msg = "Adatok sikeresen frissítve!"; }
}

// JELSZÓ MÓDOSÍTÁSA
if (!empty($_POST['new_password'])) {
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET password='$new_pass' WHERE id=$user_id");
    $msg = "Jelszó is módosítva!";
}

$u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id"));
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Profil Szerkesztése</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card" style="max-width: 500px; margin: 50px auto;">
        <h3>Profil adatok módosítása</h3>
        <?php if($msg) echo "<p class='btn-success' style='padding:10px; text-align:center;'>$msg</p>"; ?>
        
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Súly (kg)</label>
                    <input type="number" name="weight" value="<?php echo $u['weight']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Magasság (cm)</label>
                    <input type="number" name="height" value="<?php echo $u['height']; ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Életkor</label>
                <input type="number" name="age" value="<?php echo $u['age']; ?>" required>
            </div>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            
            <div class="form-group">
                <label>Új jelszó (Hagyd üresen, ha nem változik)</label>
                <input type="password" name="new_password" placeholder="******">
            </div>

            <button type="submit" name="update_profile" class="btn-primary">Változtatások mentése</button>
        </form>
        <br>
        <a href="dashboard.php" style="display:block; text-align:center; color: var(--text-muted);">Vissza a Dashboardra</a>
    </div>
</body>
</html>