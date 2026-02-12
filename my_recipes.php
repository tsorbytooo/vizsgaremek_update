<?php
session_start();
require 'database_connect.php'; 

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];

// --- TÖRLÉS LOGIKA ---
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    // Csak a saját feltöltött ételeit törölheti
    mysqli_query($conn, "DELETE FROM foods WHERE id = $del_id AND created_by = $user_id");
    header("Location: my_recipes.php"); exit();
}

// --- ÚJ ÉTEL MENTÉSE LOGIKA ---
if (isset($_POST['save_recipe'])) {
    $name = mysqli_real_escape_string($conn, $_POST['r_name']);
    $cal = (float)$_POST['r_cal'];
    $prot = (float)$_POST['r_prot'];
    $carb = (float)$_POST['r_carb'];
    $fat = (float)$_POST['r_fat'];

    $sql = "INSERT INTO foods (name, calories_100g, protein_100g, carbs_100g, fat_100g, created_by) 
            VALUES ('$name', $cal, $prot, $carb, $fat, $user_id)";
    mysqli_query($conn, $sql);
    header("Location: my_recipes.php"); exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Recepttáram</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .recipe-container { max-width: 900px; margin: 40px auto; padding: 20px; }
        .recipe-card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .recipe-card { 
            background: #1e293b; 
            border: 1px solid #334155; 
            padding: 20px; 
            border-radius: 15px; 
            position: relative;
            transition: 0.3s;
        }
        .recipe-card:hover { transform: translateY(-5px); border-color: #4361ee; }
        .macro-info { display: flex; justify-content: space-between; font-size: 12px; color: #94a3b8; margin-top: 10px; }
        .delete-icon { color: #ef4444; text-decoration: none; font-size: 18px; position: absolute; top: 15px; right: 15px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #4361ee; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body class="dark-mode"> <div class="recipe-container">
    <a href="dashboard.php" class="back-link">← Vissza a Dashboardra</a>
    
    <section class="card-section">
        <h2>📖 Recepttáram</h2>
        <p style="color: #94a3b8;">Itt mentheted el a saját ételeid tápértékeit 100 grammra vonatkoztatva.</p>

        <form method="POST" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr; gap: 10px; margin-top: 25px; align-items: end;">
            <div><label><small>Megnevezés</small></label><input type="text" name="r_name" required></div>
            <div><label><small>kcal/100g</small></label><input type="number" step="0.1" name="r_cal" required></div>
            <div><label><small>Fehérje</small></label><input type="number" step="0.1" name="r_prot" value="0"></div>
            <div><label><small>Szénh.</small></label><input type="number" step="0.1" name="r_carb" value="0"></div>
            <div><label><small>Zsír</small></label><input type="number" step="0.1" name="r_fat" value="0"></div>
            <button type="submit" name="save_recipe" class="btn-primary" style="height: 42px;">Mentés</button>
        </form>
    </section>

    <div class="recipe-card-grid">
        <?php
        $res = mysqli_query($conn, "SELECT * FROM foods WHERE created_by = $user_id ORDER BY name ASC");
        if(mysqli_num_rows($res) > 0):
            while($row = mysqli_fetch_assoc($res)): ?>
                <div class="recipe-card">
                    <a href="my_recipes.php?delete_id=<?php echo $row['id']; ?>" class="delete-icon" onclick="return confirm('Biztosan törlöd?')">×</a>
                    <strong style="font-size: 18px; color: #f8fafc;"><?php echo htmlspecialchars($row['name']); ?></strong>
                    <div style="color: #4361ee; font-weight: bold; margin-top: 5px;"><?php echo $row['calories_100g']; ?> kcal</div>
                    
                    <div class="macro-info">
                        <span>F: <?php echo $row['protein_100g']; ?>g</span>
                        <span>SZ: <?php echo $row['carbs_100g']; ?>g</span>
                        <span>ZS: <?php echo $row['fat_100g']; ?>g</span>
                    </div>
                    
                    <a href="dashboard.php?q=<?php echo urlencode($row['name']); ?>" 
                       style="display: block; margin-top: 15px; text-align: center; background: #334155; color: white; padding: 8px; border-radius: 8px; text-decoration: none; font-size: 13px;">
                       Naplózás →
                    </a>
                </div>
            <?php endwhile; 
        else: ?>
            <p style="grid-column: 1/-1; text-align: center; color: #94a3b8; padding: 40px;">Még nem mentettél el saját receptet.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>