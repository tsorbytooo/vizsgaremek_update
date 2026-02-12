<?php
session_start();
require 'database_connect.php';

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];

// JAVÍTOTT LEKÉRDEZÉS: log_date-et használunk date_added helyett
$query = "SELECT l.*, f.name as food_name, f.calories_100g, DATE(l.log_date) as log_day 
          FROM user_food_log l 
          JOIN foods f ON l.food_id = f.id 
          WHERE l.user_id = $user_id 
          AND l.log_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
          ORDER BY l.log_date DESC, l.id DESC";

$result = mysqli_query($conn, $query);

$meals_by_date = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // A csoportosításhoz a log_day (formázott dátum) mezőt használjuk
        $meals_by_date[$row['log_day']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Napló | Kalória Center</title>
    <link rel="stylesheet" href="style.css">
    <script src="theme-handler.js"></script>
    <style>
        .day-container { margin-bottom: 15px; }
        .day-header { 
            background: var(--card-bg); 
            padding: 15px; 
            border-radius: 12px; 
            cursor: pointer; 
            display: flex; 
            justify-content: space-between;
            border: 1px solid var(--border-color);
            font-weight: bold;
            transition: background 0.2s;
        }
        .day-header:hover {
            background: var(--border-color);
        }
        .day-content { 
            display: none; 
            padding: 10px; 
            background: var(--card-bg); 
            border-radius: 0 0 12px 12px;
            border: 1px solid var(--border-color);
            border-top: none;
        }
        .meal-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px; 
            border-bottom: 1px solid var(--border-color);
        }
        .meal-row:last-child { border-bottom: none; }
        
        /* Sötét mód specifikus igazítások a naplóhoz */
        body.dark-mode .day-header { background-color: #1e293b !important; }
        body.dark-mode .day-content { background-color: #0f172a !important; }
        
        .container-wide {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container-wide">
    <header style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <h1 style="margin: 0;">🗓️ Napló</h1>
        <a href="dashboard.php" class="btn-primary" style="text-decoration: none; width: auto; padding: 10px 25px;">
            ← Dashboard
        </a>
    </header>

    <?php if (empty($meals_by_date)): ?>
        <div class="card-section text-center">
            <p>Még nincs rögzített étkezésed az elmúlt 30 napban.</p>
        </div>
    <?php else: ?>
        <?php foreach ($meals_by_date as $date => $meals): 
            $daily_calories = 0;
            foreach($meals as $m) { 
                $daily_calories += ($m['calories_100g'] / 100) * $m['quantity']; 
            }
        ?>
            <div class="day-container">
                <div class="day-header" onclick="toggleDay('day-<?php echo $date; ?>')">
                    <span>📅 <?php echo $date; ?></span>
                    <span><?php echo round($daily_calories); ?> kcal ▾</span>
                </div>
                <div id="day-<?php echo $date; ?>" class="day-content">
                    <?php foreach ($meals as $meal): 
                        $meal_cal = ($meal['calories_100g'] / 100) * $meal['quantity'];
                    ?>
                        <div class="meal-row">
                            <span>
                                <strong><?php echo htmlspecialchars($meal['food_name']); ?></strong> 
                                <br><small style="color: var(--text-muted);"><?php echo (int)$meal['quantity']; ?> gramm</small>
                            </span>
                            <strong style="color: var(--primary);"><?php echo round($meal_cal); ?> kcal</strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function toggleDay(id) {
    var x = document.getElementById(id);
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        // Csak az aktuálisat nyitjuk meg, a többit bezárjuk az átláthatóságért
        document.querySelectorAll('.day-content').forEach(el => el.style.display = 'none');
        x.style.display = "block";
    }
}

// Téma betöltése a LocalStorage-ből
if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
}
</script>

</body>
</html>