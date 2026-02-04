<?php
session_start();
require 'database_connect.php'; 

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// Felhasználói adatok lekérése
$u_sql = "SELECT * FROM users WHERE id = $user_id";
$u_res = mysqli_query($conn, $u_sql);
$u_data = mysqli_fetch_assoc($u_res);

// --- KEDVENCNEK JELÖLÉS LOGIKA ---
if (isset($_GET['toggle_favorite'])) {
    $f_id = (int)$_GET['toggle_favorite'];
    $check_f = mysqli_query($conn, "SELECT id FROM favorites WHERE user_id = $user_id AND food_id = $f_id");
    if (mysqli_num_rows($check_f) > 0) {
        mysqli_query($conn, "DELETE FROM favorites WHERE user_id = $user_id AND food_id = $f_id");
    } else {
        mysqli_query($conn, "INSERT INTO favorites (user_id, food_id) VALUES ($user_id, $f_id)");
    }
    header("Location: dashboard.php"); exit();
}

// --- MANUÁLIS ÉTEL FELTÖLTÉS + KÉP ---
if (isset($_POST['add_custom_food'])) {
    $name = mysqli_real_escape_string($conn, $_POST['food_name']);
    $cal = (float)$_POST['food_cal'];
    $image_name = null;

    if (!empty($_FILES['food_image']['name'])) {
        $image_name = time() . '_' . $_FILES['food_image']['name'];
        move_uploaded_file($_FILES['food_image']['tmp_name'], 'uploads/' . $image_name);
    }

    $ins_food = "INSERT INTO foods (name, calories_100g, image, created_by) VALUES ('$name', $cal, '$image_name', $user_id)";
    if (mysqli_query($conn, $ins_food)) {
        $new_id = mysqli_insert_id($conn);
        $qty = (float)$_POST['food_qty'];
        mysqli_query($conn, "INSERT INTO user_food_log (user_id, food_id, quantity, log_date) VALUES ($user_id, $new_id, $qty, '$today')");
    }
    header("Location: dashboard.php"); exit();
}

// Kedvenc ID-k lekérése a színezéshez
$fav_ids = [];
$fav_res = mysqli_query($conn, "SELECT food_id FROM favorites WHERE user_id = $user_id");
while($f_row = mysqli_fetch_assoc($fav_res)) $fav_ids[] = $f_row['food_id'];

// --- SZÁMÍTÁSOK (BMI, Víz, Limit) ---
$bmi = 0; $limit = 2000; $water = 2; $status = "Nincs adat";
if ($u_data['weight'] > 0 && $u_data['height'] > 0) {
    $h_m = $u_data['height'] / 100;
    $bmi = round($u_data['weight'] / ($h_m * $h_m), 1);
    $water = round($u_data['weight'] * 0.035, 1);
    
    if ($u_data['gender'] == 'male') {
        $limit = (10 * $u_data['weight']) + (6.25 * $u_data['height']) - (5 * $u_data['age']) + 5;
    } else {
        $limit = (10 * $u_data['weight']) + (6.25 * $u_data['height']) - (5 * $u_data['age']) - 161;
    }
    $limit = round($limit * 1.2);

    if($bmi < 18.5) $status = "Sovány";
    elseif($bmi < 25) $status = "Ideális";
    elseif($bmi < 30) $status = "Túlsúly";
    else $status = "Elhízott";
}

// --- ÉTEL HOZZÁADÁSA ---
if (isset($_POST['add_to_log'])) {
    $food_id = (int)$_POST['food_id'];
    $qty = (float)$_POST['quantity'];
    $ins = "INSERT INTO user_food_log (user_id, food_id, quantity, log_date) VALUES ($user_id, $food_id, $qty, '$today')";
    mysqli_query($conn, $ins);
    header("Location: dashboard.php"); exit();
}

// --- TÖRLÉS ---
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM user_food_log WHERE id = $del_id AND user_id = $user_id");
    header("Location: dashboard.php"); exit();
}

// --- KERESÉS ---
$search_results = [];
if (!empty($_GET['q'])) {
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    $s_sql = "SELECT * FROM foods WHERE (name LIKE '%$q%' AND (created_by IS NULL OR created_by = $user_id)) LIMIT 5";
    $s_res = mysqli_query($conn, $s_sql);
    while($row = mysqli_fetch_assoc($s_res)) $search_results[] = $row;
}

// --- MAI ÖSSZESÍTÉS ---
$sum_sql = "SELECT SUM((f.calories_100g/100)*l.quantity) as total_cal 
            FROM user_food_log l 
            JOIN foods f ON l.food_id = f.id 
            WHERE l.user_id = $user_id AND l.log_date = '$today'";
$sum_res = mysqli_query($conn, $sum_sql);
$current_cal = mysqli_fetch_assoc($sum_res)['total_cal'] ?? 0;
$percent = ($limit > 0) ? ($current_cal / $limit) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard-container">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; position: relative;">
        <h2 style="margin: 0;">Szia, <?php echo htmlspecialchars($u_data['name']); ?>!</h2>
        
        <div style="position: relative; display: inline-block;">
            <button type="button" id="menuBtn" style="background-color: #4361ee; color: white; padding: 12px 24px; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; font-family: inherit; display: block; transition: none !important;">
                Továbbiak ▼
            </button>
            
            <div id="menuContent" style="display: none; position: absolute; right: 0; top: 55px; background-color: white; min-width: 220px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); border-radius: 12px; z-index: 99999; border: 1px solid #edf2f7; overflow: hidden;">
                <a href="profile.php" style="color: #2b2d42; padding: 14px 20px; text-decoration: none; display: block; border-bottom: 1px solid #f8f9fd;">👤 Profil szerkesztése</a>
                <a href="premium.php" style="color: #2b2d42; padding: 14px 20px; text-decoration: none; display: block; border-bottom: 1px solid #f8f9fd;">⭐ Prémium tagság</a>
                <a href="logout.php" style="color: #e71d36; padding: 14px 20px; text-decoration: none; display: block; font-weight: bold;">🚪 Kijelentkezés</a>
            </div>
        </div>
    </header>

    <script>
        const btn = document.getElementById('menuBtn');
        const box = document.getElementById('menuContent');

        btn.addEventListener('mousedown', function(e) {
            e.preventDefault();
            if (box.style.display === "block") {
                box.style.display = "none";
            } else {
                box.style.display = "block";
                box.offsetHeight; 
            }
            e.stopPropagation();
        });

        document.addEventListener('mousedown', function(e) {
            if (e.target !== btn && !box.contains(e.target)) {
                box.style.display = "none";
            }
        });
    </script>

    <div class="dashboard-grid">
        <section class="card-section">
            <h3>Napi Állapot</h3>
            <div class="info-grid">
                <div class="info-item"><span>BMI</span><strong><?php echo $bmi; ?></strong></div>
                <div class="info-item"><span>Besorolás</span><strong><?php echo $status; ?></strong></div>
                <div class="info-item"><span>Vízszükséglet</span><strong><?php echo $water; ?> L</strong></div>
                <div class="info-item"><span>Napi cél</span><strong><?php echo $limit; ?> kcal</strong></div>
            </div>
            
            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo min($percent, 100); ?>%; background-color: <?php echo $percent > 100 ? 'var(--danger)' : 'var(--success)'; ?>;">
                    <?php echo round($percent); ?>%
                </div>
            </div>
            <p style="text-align: center; margin-top: 15px; color: var(--text-muted);">
                Jelenleg: <strong><?php echo round($current_cal); ?> kcal</strong> / <?php echo $limit; ?> kcal
            </p>
        </section>

        <section class="card-section">
            <h3>Mit ettél ma?</h3>
            <form action="dashboard.php" method="GET" class="search-form">
                <input type="text" name="q" placeholder="Étel keresése..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                <button type="submit" class="btn-primary">Keresés</button>
            </form>

            <?php
            $my_favs_sql = "SELECT f.* FROM foods f JOIN favorites fav ON f.id = fav.food_id WHERE fav.user_id = $user_id";
            $my_favs_res = mysqli_query($conn, $my_favs_sql);
            if (mysqli_num_rows($my_favs_res) > 0): ?>
                <div style="margin-bottom: 20px; padding: 10px; background: #fffcf0; border-radius: 12px; border: 1px solid #ffeeba;">
                    <small style="color: #ff9f1c; font-weight: bold; display: block; margin-bottom: 5px;">Kedvenceid:</small>
                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                        <?php while($mf = mysqli_fetch_assoc($my_favs_res)): ?>
                            <a href="dashboard.php?q=<?php echo urlencode($mf['name']); ?>" style="padding: 4px 10px; font-size: 11px; background-color: #ff9f1c; color: white; text-decoration: none; border-radius: 8px;">
                                <?php echo htmlspecialchars($mf['name']); ?>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty($search_results)): ?>
                <table>
                    <?php foreach($search_results as $f): 
                        $is_fav = in_array($f['id'], $fav_ids);
                    ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <a href="dashboard.php?toggle_favorite=<?php echo $f['id']; ?>" style="text-decoration: none; font-size: 20px; color: <?php echo $is_fav ? '#ff9f1c' : '#ccc'; ?>;">
                                    <?php echo $is_fav ? '★' : '☆'; ?>
                                </a>
                                <?php if($f['image']): ?>
                                    <img src="uploads/<?php echo $f['image']; ?>" style="width: 40px; height: 40px; border-radius: 5px; object-fit: cover;">
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo $f['name']; ?></strong><br>
                                    <small><?php echo $f['calories_100g']; ?> kcal / 100g</small>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <form method="POST" style="display: flex; gap: 5px; justify-content: flex-end;">
                                <input type="hidden" name="food_id" value="<?php echo $f['id']; ?>">
                                <input type="number" name="quantity" value="100" style="width: 60px;">
                                <button type="submit" name="add_to_log" class="btn-success">+</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php elseif(isset($_GET['q'])): ?>
                <div style="background: #f1f4ff; padding: 15px; border-radius: 12px; margin-top: 10px;">
                    <p style="font-size: 14px; font-weight: bold;">Nincs ilyen étel? Add hozzá manuálisan!</p>
                    <form method="POST" enctype="multipart/form-data" style="display: grid; gap: 10px;">
                        <input type="text" name="food_name" value="<?php echo htmlspecialchars($_GET['q']); ?>" placeholder="Étel neve" required>
                        <div style="display: flex; gap: 10px;">
                            <input type="number" name="food_cal" placeholder="kcal/100g" required>
                            <input type="number" name="food_qty" placeholder="Megevett g" required>
                        </div>
                        <label style="font-size: 12px; color: var(--text-muted);">Kép feltöltése (opcionális):</label>
                        <input type="file" name="food_image" accept="image/*">
                        <button type="submit" name="add_custom_food" class="btn-primary">Hozzáadás</button>
                    </form>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <section class="card-section">
        <h3>Mai napló</h3>
        <table>
            <thead>
                <tr>
                    <th>Étel</th>
                    <th>Mennyiség</th>
                    <th>Kalória</th>
                    <th style="text-align: right;">Művelet</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $logs_sql = "SELECT l.id, f.name, l.quantity, f.calories_100g, f.image 
                             FROM user_food_log l 
                             JOIN foods f ON l.food_id = f.id 
                             WHERE l.user_id = $user_id AND l.log_date = '$today'
                             ORDER BY l.id DESC";
                $logs_res = mysqli_query($conn, $logs_sql);
                if(mysqli_num_rows($logs_res) == 0): ?>
                    <tr><td colspan="4" style="text-align:center; color: var(--text-muted);">Még nem ettél semmit ma.</td></tr>
                <?php endif;
                while($l = mysqli_fetch_assoc($logs_res)):
                    $cal = ($l['calories_100g'] / 100) * $l['quantity'];
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <?php if($l['image']): ?>
                                <img src="uploads/<?php echo $l['image']; ?>" style="width: 30px; height: 30px; border-radius: 4px; object-fit: cover;">
                            <?php endif; ?>
                            <strong><?php echo htmlspecialchars($l['name']); ?></strong>
                        </div>
                    </td>
                    <td><?php echo (int)$l['quantity']; ?> g</td>
                    <td><?php echo round($cal); ?> kcal</td>
                    <td style="text-align: right;">
                        <a href="dashboard.php?delete_id=<?php echo $l['id']; ?>" class="delete-btn" onclick="return confirm('Biztosan törlöd?')">Törlés</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</div>

</body>
</html>