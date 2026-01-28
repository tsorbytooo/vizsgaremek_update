<?php
session_start();
require 'database_connect.php'; 

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// --- 1. ÚJ ÉTEL FELVITELE (Közösségi beküldés) ---
if (isset($_POST['save_new_food'])) {
    $name = mysqli_real_escape_string($conn, $_POST['new_food_name']);
    $cal = (float)$_POST['new_food_cal'];
    $prot = (float)($_POST['new_food_prot'] ?? 0);
    $carb = (float)($_POST['new_food_carb'] ?? 0);
    $fat = (float)($_POST['new_food_fat'] ?? 0);

    $insert_sql = "INSERT INTO foods (name, calories_100g, protein_100g, carbs_100g, fat_100g, source) 
                   VALUES ('$name', $cal, $prot, $carb, $fat, 'user_contributed')";
    
    if (mysqli_query($conn, $insert_sql)) {
        $new_id = mysqli_insert_id($conn);
        mysqli_query($conn, "INSERT INTO user_food_log (user_id, food_id, quantity, log_date) 
                             VALUES ($user_id, $new_id, 100, '$today')");
        header("Location: dashboard.php?msg=Sikeres mentés!");
        exit();
    }
}

// --- 2. TÖRLÉS ÉS HOZZÁADÁS ---
if (isset($_GET['delete_id'])) {
    mysqli_query($conn, "DELETE FROM user_food_log WHERE id=".(int)$_GET['delete_id']." AND user_id=$user_id");
    header("Location: dashboard.php"); exit();
}

if (isset($_POST['add_to_log'])) {
    $fid = (int)$_POST['food_id'];
    $qty = (float)$_POST['quantity'];
    mysqli_query($conn, "INSERT INTO user_food_log (user_id, food_id, quantity, log_date) VALUES ($user_id, $fid, $qty, '$today')");
    header("Location: dashboard.php"); exit();
}

// --- 3. ADATOK LEKÉRÉSE ÉS SZÁMÍTÁSOK ---
$user_res = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
$u_data = mysqli_fetch_assoc($user_res);

$bmi = 0; $daily_limit = 2000; $water_limit = 2; $status = "Nincs adat";

if ($u_data['weight'] > 0 && $u_data['height'] > 0) {
    $height_m = $u_data['height'] / 100;
    $bmi = round($u_data['weight'] / ($height_m * $height_m), 1);
    $water_limit = round(($u_data['weight'] * 0.035), 1);

    if ($u_data['gender'] == 'male') {
        $daily_limit = (10 * $u_data['weight']) + (6.25 * $u_data['height']) - (5 * $u_data['age']) + 5;
    } else {
        $daily_limit = (10 * $u_data['weight']) + (6.25 * $u_data['height']) - (5 * $u_data['age']) - 161;
    }
    $daily_limit = round($daily_limit * 1.2);

    if ($bmi < 18.5) $status = "Sovány";
    elseif ($bmi < 25) $status = "Ideális";
    elseif ($bmi < 30) $status = "Túlsúlyos";
    else $status = "Elhízott";
}

// KERESÉS
$results = [];
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    $res = mysqli_query($conn, "SELECT * FROM foods WHERE name LIKE '%$q%' LIMIT 5");
    while($r = mysqli_fetch_assoc($res)) $results[] = $r;
}

// ÖSSZESÍTÉS
$log_sum = mysqli_query($conn, "SELECT SUM((f.calories_100g/100)*l.quantity) as total FROM user_food_log l JOIN foods f ON l.food_id=f.id WHERE l.user_id=$user_id AND l.log_date='$today'");
$current_total = mysqli_fetch_assoc($log_sum)['total'] ?? 0;
$percent = ($daily_limit > 0) ? ($current_total / $daily_limit) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIGMA Kalória</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard-container">
    
    <header style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
        <h2 style="margin:0;">Szia, <?php echo htmlspecialchars($u_data['name']); ?>!</h2>
        <a href="logout.php" class="delete-btn" style="padding:10px 20px;">Kijelentkezés</a>
    </header>

    <div class="dashboard-grid">
        
        <section class="card-section">
            <h3>Állapotjelző</h3>
            <div class="info-grid">
                <div class="info-item"><span>Besorolás</span><strong><?php echo $status; ?></strong></div>
                <div class="info-item"><span>BMI Index</span><strong><?php echo $bmi; ?></strong></div>
                <div class="info-item"><span>Vízszükséglet</span><strong><?php echo $water_limit; ?> L</strong></div>
                <div class="info-item"><span>Napi cél</span><strong><?php echo $daily_limit; ?> kcal</strong></div>
            </div>
            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo min($percent, 100); ?>%; background: <?php echo $percent > 100 ? '#e53e3e' : '#38a169'; ?>">
                    <?php echo round($percent); ?>%
                </div>
            </div>
            <p style="text-align:center; font-size:14px; margin-top:10px; color:#666;">
                Bevitel: <strong><?php echo round($current_total); ?></strong> / <?php echo $daily_limit; ?> kcal
            </p>
        </section>

        <section class="card-section">
            <h3>Mit ettél ma?</h3>
            <form action="dashboard.php" method="GET" style="display:flex; gap:10px;">
                <input type="text" name="q" placeholder="Étel keresése..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" style="margin:0;">
                <button type="submit" class="btn-primary" style="width:auto; margin:0;">Keresés</button>
            </form>

            <?php if(isset($_GET['q']) && empty($results)): ?>
                <div style="background: #fff5f5; border: 1px dashed #e53e3e; padding: 15px; border-radius: 10px; margin-top: 15px;">
                    <p style="color: #c53030; font-weight: bold; font-size:14px; margin-bottom: 10px; text-align:center;">Nincs találat. Add meg manuálisan:</p>
                    <form method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                        <input type="text" name="new_food_name" value="<?php echo htmlspecialchars($_GET['q']); ?>" required>
                        <div style="display: flex; gap: 8px;">
                            <input type="number" step="0.1" name="new_food_cal" placeholder="Kcal / 100g" required>
                            <input type="number" step="0.1" name="new_food_prot" placeholder="Fehérje (g)">
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <input type="number" step="0.1" name="new_food_carb" placeholder="Szénhidrát (g)">
                            <input type="number" step="0.1" name="new_food_fat" placeholder="Zsír (g)">
                        </div>
                        <button type="submit" name="save_new_food" class="btn-success">Mentés és Hozzáadás</button>
                    </form>
                </div>
            <?php elseif($results): ?>
                <table style="margin-top:15px; font-size:14px;">
                    <?php foreach($results as $f): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($f['name']); ?></strong><br><small><?php echo $f['calories_100g']; ?> kcal/100g</small></td>
                        <td style="text-align:right;">
                            <form method="POST" style="display:flex; gap:5px; justify-content:flex-end;">
                                <input type="hidden" name="food_id" value="<?php echo $f['id']; ?>">
                                <input type="number" name="quantity" value="100" style="width:55px; padding:5px;">
                                <button type="submit" name="add_to_log" class="btn-success" style="padding:5px 12px;">+</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </section>

    </div>

    <section class="card-section" style="margin-top:20px;">
        <h3>Mai étkezések naplója</h3>
        <table>
            <thead>
                <tr>
                    <th>Megnevezés</th>
                    <th>Adag (g)</th>
                    <th>Energia</th>
                    <th style="text-align:right;">Művelet</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $logs = mysqli_query($conn, "SELECT l.id, f.name, l.quantity, f.calories_100g FROM user_food_log l JOIN foods f ON l.food_id=f.id WHERE l.user_id=$user_id AND l.log_date='$today' ORDER BY l.id DESC");
                if(mysqli_num_rows($logs) == 0) echo "<tr><td colspan='4' style='text-align:center; color:#999;'>Még nem ettél semmit ma.</td></tr>";
                while($l = mysqli_fetch_assoc($logs)):
                    $c = ($l['calories_100g']/100)*$l['quantity'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($l['name']); ?></td>
                    <td><?php echo (int)$l['quantity']; ?> g</td>
                    <td><strong><?php echo round($c); ?> kcal</strong></td>
                    <td style="text-align:right;">
                        <a href="dashboard.php?delete_id=<?php echo $l['id']; ?>" class="delete-btn">Törlés</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

</div>

</body>
</html>