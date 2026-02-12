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

// --- VÍZ RENDSZER LOGIKA (ÚJ) ---
if (isset($_POST['add_water'])) {
    $amount = (float)$_POST['water_amount'];
    mysqli_query($conn, "INSERT INTO water_log (user_id, amount, log_date) VALUES ($user_id, $amount, '$today')");
    header("Location: dashboard.php"); exit();
}

if (isset($_POST['reset_water'])) {
    mysqli_query($conn, "DELETE FROM water_log WHERE user_id = $user_id AND log_date = '$today'");
    header("Location: dashboard.php"); exit();
}

// Aktuális vízállás lekérése
$water_query = mysqli_query($conn, "SELECT SUM(amount) as total FROM water_log WHERE user_id = $user_id AND log_date = '$today'");
$water_row = mysqli_fetch_assoc($water_query);
$current_water = (float)($water_row['total'] ?? 0);

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

// --- API ÉTEL MENTÉSE ÉS NAPLÓZÁSA ---
if (isset($_POST['add_api_food'])) {
    $name = mysqli_real_escape_string($conn, $_POST['api_name']);
    $cal = (float)$_POST['api_cal'];
    $prot = (float)$_POST['api_prot'] ?? 0;
    $carb = (float)$_POST['api_carb'] ?? 0;
    $fat = (float)$_POST['api_fat'] ?? 0;
    $qty = (float)$_POST['quantity'];

    $ins_f = "INSERT INTO foods (name, calories_100g, protein_100g, carbs_100g, fat_100g, created_by) 
              VALUES ('$name', $cal, $prot, $carb, $fat, $user_id)";
    if (mysqli_query($conn, $ins_f)) {
        $new_id = mysqli_insert_id($conn);
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

$water_percent = ($water > 0) ? ($current_water / $water) * 100 : 0;

// --- ÚJ KOMBINÁLT HOZZÁADÁS (GRAMM + DARAB) ---
if (isset($_POST['add_combined_to_log'])) {
    $food_id = (int)$_POST['food_id'];
    $extra_grams = (float)$_POST['quantity'];
    $pieces = (float)$_POST['pieces'];
    $piece_weight = 150; 
    $total_qty = ($pieces * $piece_weight) + $extra_grams;

    if ($total_qty > 0) {
        $ins = "INSERT INTO user_food_log (user_id, food_id, quantity, log_date) VALUES ($user_id, $food_id, $total_qty, '$today')";
        mysqli_query($conn, $ins);
    }
    header("Location: dashboard.php"); exit();
}

// --- RÉGI METÓDUSOK MEGTARTÁSA ---
if (isset($_POST['add_to_log'])) {
    $food_id = (int)$_POST['food_id'];
    $qty = (float)$_POST['quantity'];
    $ins = "INSERT INTO user_food_log (user_id, food_id, quantity, log_date) VALUES ($user_id, $food_id, $qty, '$today')";
    mysqli_query($conn, $ins);
    header("Location: dashboard.php"); exit();
}
if (isset($_POST['add_piece_to_log'])) {
    $food_id = (int)$_POST['food_id'];
    $pieces = (float)$_POST['pieces'];
    $piece_weight = 150; 
    $total_qty = $pieces * $piece_weight;
    $ins = "INSERT INTO user_food_log (user_id, food_id, quantity, log_date) VALUES ($user_id, $food_id, $total_qty, '$today')";
    mysqli_query($conn, $ins);
    header("Location: dashboard.php"); exit();
}

// --- TÖRLÉS ---
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM user_food_log WHERE id = $del_id AND user_id = $user_id");
    header("Location: dashboard.php"); exit();
}

// --- KERESÉS (HELYI + API) ---
$search_results = [];
if (!empty($_GET['q'])) {
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    
    // 1. Helyi adatbázis
    $s_sql = "SELECT * FROM foods WHERE (name LIKE '%$q%' AND (created_by IS NULL OR created_by = $user_id)) LIMIT 5";
    $s_res = mysqli_query($conn, $s_sql);
    while($row = mysqli_fetch_assoc($s_res)) {
        $row['is_api'] = false;
        $search_results[] = $row;
    }

    // 2. Edamam API (Ha nincs elég találat helyben)
    if (count($search_results) < 3) {
        $app_id = "74bf4d72"; 
        $app_key = "8f5d9917069f64762b9297d77890eda3";
        
        $translate = ["sajtburger" => "cheeseburger", "csirke" => "chicken", "alma" => "apple", "kenyér" => "bread"];
        $api_q = isset($translate[strtolower($_GET['q'])]) ? $translate[strtolower($_GET['q'])] : $_GET['q'];

        $url = "https://api.edamam.com/api/food-database/v2/parser?app_id=$app_id&app_key=$app_key&ingr=" . urlencode($api_q);
        $response = @file_get_contents($url);
        if ($response) {
            $api_data = json_decode($response, true);
            if (isset($api_data['hints'])) {
                foreach (array_slice($api_data['hints'], 0, 5) as $item) {
                    $f = $item['food'];
                    $search_results[] = [
                        'id' => $f['foodId'],
                        'name' => "🌍 " . $f['label'],
                        'calories_100g' => $f['nutrients']['ENERC_KCAL'] ?? 0,
                        'protein_100g' => $f['nutrients']['PROCNT'] ?? 0,
                        'carbs_100g' => $f['nutrients']['CHOCDF'] ?? 0,
                        'fat_100g' => $f['nutrients']['FAT'] ?? 0,
                        'image' => $f['image'] ?? null,
                        'is_api' => true
                    ];
                }
            }
        }
    }
}

// --- MAI ÖSSZESÍTÉS ---
$sum_sql = "SELECT 
                SUM((f.calories_100g/100)*l.quantity) as total_cal,
                SUM((f.protein_100g/100)*l.quantity) as total_protein,
                SUM((f.carbs_100g/100)*l.quantity) as total_carbs,
                SUM((f.fat_100g/100)*l.quantity) as total_fat
            FROM user_food_log l 
            JOIN foods f ON l.food_id = f.id 
            WHERE l.user_id = $user_id AND l.log_date = '$today'";
$sum_res = mysqli_query($conn, $sum_sql);
$daily_data = mysqli_fetch_assoc($sum_res);

$current_cal = $daily_data['total_cal'] ?? 0;
$current_protein = $daily_data['total_protein'] ?? 0;
$current_carbs = $daily_data['total_carbs'] ?? 0;
$current_fat = $daily_data['total_fat'] ?? 0;

$percent = ($limit > 0) ? ($current_cal / $limit) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* SÖTÉT TÉMA STÍLUSOK */
        body.dark-mode {
            background-color: #0f172a !important;
            color: #f8fafc !important;
        }
        body.dark-mode .card-section {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        body.dark-mode input {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        body.dark-mode #menuContent {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        body.dark-mode #menuContent a {
            color: #f8fafc !important;
            border-bottom-color: #334155 !important;
        }
        body.dark-mode table, body.dark-mode tr, body.dark-mode td {
            border-color: #334155 !important;
        }
        body.dark-mode .info-item {
            background-color: #0f172a !important;
        }
        body.dark-mode .fav-box {
            background-color: #334155 !important;
            border-color: #475569 !important;
            color: #f8fafc !important;
        }
        body.dark-mode .manual-add-box {
            background-color: #0f172a !important;
            color: #f8fafc !important;
        }
        body.dark-mode .premium-blur-box {
            background-color: #334155 !important;
            border-color: #475569 !important;
        }
        body.dark-mode .premium-overlay {
            background: rgba(30, 41, 59, 0.8) !important;
        }
        body.dark-mode .premium-overlay strong {
            color: #f8fafc !important;
        }

        .progress-container {
            height: 30px !important;
            background-color: #e2e8f0;
            border-radius: 15px;
            overflow: hidden;
            margin-top: 15px;
        }
        .progress-bar {
            height: 30px !important;
            line-height: 30px !important;
            font-size: 15px !important;
            font-weight: bold;
            color: white;
            text-align: center;
            display: block; 
        }

        .uniform-btn {
            width: auto !important; 
            padding: 0 20px;
            height: 40px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: white;
            white-space: nowrap;
        }

        /* EGY SORBA RENDEZÉS CSS */
        .combined-log-form {
            display: flex;
            flex-direction: row; 
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            width: 100%;
        }

        .log-input-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .log-input-group input {
            width: 60px !important;
            padding: 8px !important;
            border-radius: 8px !important;
            border: 1px solid #ddd !important;
            text-align: center;
        }

        .search-results-table td {
            padding: 15px 0;
        }

        /* VÍZ RENDSZER EXTRA STÍLUS */
        .water-card {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            border: 1px solid #7dd3fc;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        body.dark-mode .water-card {
            background: linear-gradient(135deg, #0c4a6e 0%, #075985 100%);
            border-color: #0369a1;
        }
        .water-btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }
        .w-btn {
            background: #0ea5e9;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.1s;
        }
        .w-btn:active { transform: scale(0.95); }
    </style>
</head>
<body>

<div class="dashboard-container">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; position: relative;">
        <h2 style="margin: 0;">Szia, <?php echo htmlspecialchars($u_data['name']); ?>!</h2>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            <button id="themeToggle" style="background: white; border: 1px solid #ddd; padding: 10px; border-radius: 50%; cursor: pointer; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <span id="themeIcon">🌙</span>
            </button>

            <div style="position: relative; display: inline-block;">
                <button type="button" id="menuBtn" style="background-color: #4361ee; color: white; padding: 12px 24px; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; font-family: inherit; display: block; transition: none !important;">
                    Továbbiak ▼
                </button>
                
                <div id="menuContent" style="display: none; position: absolute; right: 0; top: 55px; background-color: white; min-width: 220px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); border-radius: 12px; z-index: 99999; border: 1px solid #edf2f7; overflow: hidden;">
                    <a href="profile.php" style="color: #2b2d42; padding: 14px 20px; text-decoration: none; display: block; border-bottom: 1px solid #f8f9fd;">👤 Profil szerkesztése</a>
                    <a href="premium.php" style="color: #2b2d42; padding: 14px 20px; text-decoration: none; display: block; border-bottom: 1px solid #f8f9fd;">⭐ Prémium tagság</a>
                    <a href="my_recipes.php" style="color: #2b2d42; padding: 14px 20px; text-decoration: none; display: block; border-bottom: 1px solid #f8f9fd;">📖 Saját Recepttáram</a>
                    <a href="logout.php" style="color: #e71d36; padding: 14px 20px; text-decoration: none; display: block; font-weight: bold;">🚪 Kijelentkezés</a>
                    <a href="support.php" style="color: #2b2d42; padding: 14px 20px; text-decoration: none; display: block; border-bottom: 1px solid #f8f9fd;">📧 Support & Feedback</a>
                    <a href="about.php" style="color: #2b2d42; padding: 14px 20px; text-decoration: none; display: block; border-bottom: 1px solid #f8f9fd;">ℹ️ Rólunk</a>
                    <a href="help.php" style="color: #2b2d42; padding: 14px 20px; text-decoration: none; display: block; border-bottom: 1px solid #f8f9fd;">❓ Segítség / GYIK</a>
                    <?php if ($user_id == 9): ?>
                        <a href="admin.php" style="color: #000000 !important; padding: 14px 20px; text-decoration: none; display: block; font-weight: bold; background-color: #ffca28 !important; border-bottom: 1px solid #e0a800; text-align: center;">🛠️ ADMIN PANEL</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <script>
        const btn = document.getElementById('menuBtn');
        const box = document.getElementById('menuContent');
        const themeBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            themeIcon.innerText = '☀️';
            themeBtn.style.backgroundColor = '#1e293b';
            themeBtn.style.borderColor = '#334155';
        }

        themeBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeIcon.innerText = isDark ? '☀️' : '🌙';
            
            if(isDark) {
                themeBtn.style.backgroundColor = '#1e293b';
                themeBtn.style.borderColor = '#334155';
            } else {
                themeBtn.style.backgroundColor = 'white';
                themeBtn.style.borderColor = '#ddd';
            }
        });

        btn.addEventListener('mousedown', function(e) {
            e.preventDefault();
            if (box.style.display === "block") { box.style.display = "none"; } else { box.style.display = "block"; box.offsetHeight; }
            e.stopPropagation();
        });
        document.addEventListener('mousedown', function(e) {
            if (e.target !== btn && !box.contains(e.target)) { box.style.display = "none"; }
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
                <div class="progress-bar" style="width: <?php echo min($percent, 100); ?>%; background-color: <?php echo $percent > 100 ? '#ef4444' : '#10b981'; ?>;">
                    <?php echo round($percent); ?>%
                </div>
            </div>
            <p style="text-align: center; margin-top: 15px; color: var(--text-muted);">
                Jelenleg: <strong><?php echo round($current_cal); ?> kcal</strong> / <?php echo $limit; ?> kcal
            </p>

            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
                <h4 style="margin: 0 0 15px 0;">Makrók (Napi bevitel)</h4>

                <?php if ($u_data['premium'] == 1): ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; text-align: center;">
                        
                        <div>
                            <small style="color: #4361ee; font-weight: bold;">Fehérje</small>
                            <div style="background: #e0e7ff; height: 8px; border-radius: 4px; margin: 5px 0; overflow: hidden;">
                                <div style="background: #4361ee; height: 100%; width: 100%;"></div> 
                            </div>
                            <small><?php echo round($current_protein); ?>g</small>
                        </div>

                        <div>
                            <small style="color: #f72585; font-weight: bold;">Szénhidrát</small>
                            <div style="background: #ffe0f0; height: 8px; border-radius: 4px; margin: 5px 0; overflow: hidden;">
                                <div style="background: #f72585; height: 100%; width: 100%;"></div>
                            </div>
                            <small><?php echo round($current_carbs); ?>g</small>
                        </div>

                        <div>
                            <small style="color: #f8961e; font-weight: bold;">Zsír</small>
                            <div style="background: #fff5cc; height: 8px; border-radius: 4px; margin: 5px 0; overflow: hidden;">
                                <div style="background: #f8961e; height: 100%; width: 100%;"></div>
                            </div>
                            <small><?php echo round($current_fat); ?>g</small>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="premium-blur-box" style="position: relative; overflow: hidden; border-radius: 10px; background: #f8f9fa; border: 1px dashed #ccc; padding: 20px; text-align: center;">
                        <div style="filter: blur(4px); opacity: 0.5; user-select: none;">
                            Fehérje: 120g | Szénhidrát: 200g | Zsír: 50g
                        </div>
                        <div class="premium-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(255,255,255,0.6);">
                            <strong style="color: #2b2d42; margin-bottom: 5px;">🔒 Prémium funkció</strong>
                            <a href="premium.php" style="background: #4361ee; color: white; padding: 8px 15px; border-radius: 20px; text-decoration: none; font-size: 13px; font-weight: bold;">Előfizetés</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            </section>

        <section class="card-section">
            <h3>Mit ettél ma?</h3>
            <form action="dashboard.php" method="GET" class="search-form">
                <input type="text" name="q" placeholder="Étel keresése..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                <button type="submit" class="btn-primary">Keresés</button>
            </form>

            <div class="water-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h4 style="margin:0; color: #0369a1;">💧 Napi vízfogyasztás</h4>
                    <span style="font-weight: bold; color: #0369a1;"><?php echo $current_water; ?> / <?php echo $water; ?> L</span>
                </div>
                <div class="progress-container" style="background: rgba(255,255,255,0.5);">
                    <div class="progress-bar" style="width: <?php echo min($water_percent, 100); ?>%; background-color: #0ea5e9;">
                        <?php echo round($water_percent); ?>%
                    </div>
                </div>
                <div class="water-btn-group">
                    <form method="POST"><input type="hidden" name="water_amount" value="0.25"><button type="submit" name="add_water" class="w-btn">+ 2.5dl</button></form>
                    <form method="POST"><input type="hidden" name="water_amount" value="0.5"><button type="submit" name="add_water" class="w-btn">+ 5dl</button></form>
                    <form method="POST"><input type="hidden" name="water_amount" value="1.0"><button type="submit" name="add_water" class="w-btn">+ 1L</button></form>
                    <form method="POST" onsubmit="return confirm('Nullázod a mai vizet?')"><button type="submit" name="reset_water" class="w-btn" style="background:#64748b;">🔄</button></form>
                </div>
            </div>

            <?php
            $my_favs_sql = "SELECT f.* FROM foods f JOIN favorites fav ON f.id = fav.food_id WHERE fav.user_id = $user_id";
            $my_favs_res = mysqli_query($conn, $my_favs_sql);
            if (mysqli_num_rows($my_favs_res) > 0): ?>
                <div class="fav-box" style="margin-bottom: 20px; padding: 10px; background: #fffcf0; border-radius: 12px; border: 1px solid #ffeeba;">
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
                <table class="search-results-table" style="width: 100%; border-collapse: collapse;">
                    <?php foreach($search_results as $f): 
                        $is_fav = in_array($f['id'] ?? 0, $fav_ids);
                    ?>
                    <tr>
                        <td style="vertical-align: middle;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <?php if(!(isset($f['is_api']) && $f['is_api'])): ?>
                                <a href="dashboard.php?toggle_favorite=<?php echo $f['id']; ?>" style="text-decoration: none; font-size: 20px; color: <?php echo $is_fav ? '#ff9f1c' : '#ccc'; ?>;">
                                    <?php echo $is_fav ? '★' : '☆'; ?>
                                </a>
                                <?php else: ?>
                                    <span style="font-size: 18px;">🌍</span>
                                <?php endif; ?>

                                <?php if(!empty($f['image'])): ?>
                                    <img src="<?php echo (isset($f['is_api']) && $f['is_api']) ? $f['image'] : 'uploads/'.$f['image']; ?>" style="width: 40px; height: 40px; border-radius: 5px; object-fit: cover;">
                                <?php endif; ?>
                                <div style="display: flex; flex-direction: column; justify-content: center;">
                                    <strong><?php echo $f['name']; ?></strong>
                                    <small><?php echo $f['calories_100g']; ?> kcal / 100g</small>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: right; vertical-align: middle;">
                            <?php if(isset($f['is_api']) && $f['is_api']): ?>
                                <form method="POST" class="combined-log-form">
                                    <input type="hidden" name="api_name" value="<?php echo htmlspecialchars($f['name']); ?>">
                                    <input type="hidden" name="api_cal" value="<?php echo $f['calories_100g']; ?>">
                                    <input type="hidden" name="api_prot" value="<?php echo $f['protein_100g']; ?>">
                                    <input type="hidden" name="api_carb" value="<?php echo $f['carbs_100g']; ?>">
                                    <input type="hidden" name="api_fat" value="<?php echo $f['fat_100g']; ?>">
                                    <div class="log-input-group">
                                        <input type="number" name="quantity" value="100">
                                    </div>
                                    <button type="submit" name="add_api_food" class="uniform-btn" style="background-color: #2ec4b6;">Mentés</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" class="combined-log-form">
                                    <input type="hidden" name="food_id" value="<?php echo $f['id']; ?>">
                                    <div class="log-input-group">
                                        <span style="font-size: 12px; color: gray;">+ g:</span>
                                        <input type="number" name="quantity" value="0">
                                    </div>
                                    <div class="log-input-group">
                                        <span style="font-size: 12px; color: gray;">db:</span>
                                        <input type="number" name="pieces" value="1" step="0.5">
                                    </div>
                                    <button type="submit" name="add_combined_to_log" class="uniform-btn" style="background-color: #4361ee;">Hozzáadás</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php elseif(isset($_GET['q'])): ?>
                <div class="manual-add-box" style="background: #f1f4ff; padding: 15px; border-radius: 12px; margin-top: 10px;">
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