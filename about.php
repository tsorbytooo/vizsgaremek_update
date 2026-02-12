<?php
session_start();
require 'database_connect.php'; 

$user_id = $_SESSION['user_id'] ?? null;
$u_data = ['name' => 'Vendég'];
if ($user_id) {
    $u_res = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
    $u_data = mysqli_fetch_assoc($u_res);
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Rólunk - Kalória Center</title>
    <link rel="stylesheet" href="style.css">
    <script src="theme-handler.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative; /* Ez kell az alul lévő verzióhoz */
        }

        body.dark-mode { 
            background-color: #0f172a !important; 
            color: #f8fafc !important; 
        }

        body.dark-mode .card-section { 
            background-color: #1e293b !important; 
            border-color: #334155 !important; 
        }
        
        .about-container { 
            max-width: 800px; 
            margin: 0 auto; 
            padding: 50px 20px 100px 20px; /* Alul több hely a verziónak */
        }

        .feature-list { list-style: none; padding: 0; }
        .feature-list li { margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        
        .back-btn { 
            display: inline-block; 
            margin-top: 20px; 
            text-decoration: none; 
            color: #4361ee; 
            font-weight: bold; 
            transition: 0.3s;
        }
        .back-btn:hover { opacity: 0.8; }

        /* VERZIÓSZÁM ABLAK - KÖZÉPEN ALUL */
        .version-badge {
            position: fixed; /* Ott marad akkor is, ha görgetsz */
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            color: #94a3b8;
            letter-spacing: 1px;
            z-index: 1000;
        }

        body.dark-mode .version-badge {
            background: rgba(30, 41, 59, 0.5);
            border-color: #334155;
        }
    </style>
</head>
<body class="<?php echo ($u_data['theme'] ?? '') == 'dark' ? 'dark-mode' : ''; ?>">

<div class="about-container">
    <section class="card-section" style="padding: 40px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Rólunk</h1>
        <p style="font-size: 1.1rem; line-height: 1.6; color: #64748b;">
            A <strong>Kalória Center</strong> célja egyszerű: megkönnyíteni a kalóriaszámlálást és az egészséges életmódot. Nem csak egy app vagyunk, hanem egy közösség, ahol a tudatosság és a támogatás kéz a kézben jár. Legyen szó kezdőkről vagy tapasztaltabbakról, nálunk mindenki megtalálja a helyét.
        </p>
        
        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee; opacity: 0.5;">

        <div style="text-align: left; max-width: 600px; margin: 0 auto;">
            <h3 style="color: #4361ee;">Miben vagyunk mások?</h3>
            <p>Sok app túl van bonyolítva. Mi a gyorsaságra és a precizitásra fókuszálunk. A sötét mód és a kedvencek funkció pedig segít abban, hogy a naplózás ne nyűg, hanem rutin legyen.</p>
            
            <ul class="feature-list">
                <li>🔹 <strong>Hibrid bevitel:</strong> Gramm és darabszám egyszerre.</li>
                <li>🔹 <strong>Intelligens BMI:</strong> Valós idejű állapotkövetés.</li>
                <li>🔹 <strong>Gyors keresés:</strong> Találd meg az ételeidet pillanatok alatt.</li>
            </ul>
        </div>

        <a href="dashboard.php" class="back-btn">← Vissza a Dashboardra</a>
    </section>
</div>

<div class="version-badge">
    VERZIÓ 1.0.2 • BUILD 2026
</div>

<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
</script>

</body>
</html>