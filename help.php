<?php
session_start();
require 'database_connect.php'; 

$user_id = $_SESSION['user_id'] ?? null;
$u_data = ['theme' => 'light'];

if ($user_id) {
    // Érdemes a lekérdezést kicsit biztonságosabbá tenni, ha az id nem fix szám
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $u_res = mysqli_query($conn, "SELECT theme FROM users WHERE id = $user_id");
    if ($u_res && mysqli_num_rows($u_res) > 0) {
        $u_data = mysqli_fetch_assoc($u_res);
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Segítség & GYIK - Kalória Center</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { transition: background 0.3s; padding-bottom: 60px; }
        body.dark-mode { background-color: #0f172a !important; color: #f8fafc !important; }
        
        .faq-container { max-width: 700px; margin: 50px auto; padding: 0 20px; }
        .faq-header { text-align: center; margin-bottom: 40px; }

        .faq-item {
            background: white;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #edf2f7;
            transition: 0.3s;
        }
        body.dark-mode .faq-item { background: #1e293b; border-color: #334155; }

        .faq-question {
            padding: 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            user-select: none;
        }
        .faq-question:hover { background: #f8fafc; }
        body.dark-mode .faq-question:hover { background: #2d3748; }

        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease-out;
            color: #64748b;
            line-height: 1.6;
        }
        body.dark-mode .faq-answer { color: #94a3b8; }

        .faq-item.active .faq-answer {
            padding: 10px 20px 25px 20px;
            max-height: 200px;
        }
        .faq-item.active .icon { transform: rotate(180deg); }
        .icon { transition: 0.3s; }

        .back-link { display: block; text-align: center; margin-top: 30px; color: #4361ee; text-decoration: none; font-weight: bold; }
        
        .version-badge {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            background: rgba(148, 163, 184, 0.1); padding: 5px 15px; border-radius: 20px;
            font-size: 11px; color: #94a3b8; z-index: 1000;
        }
    </style>
</head>
<body class="<?php echo (isset($u_data['theme']) && $u_data['theme'] == 'dark') ? 'dark-mode' : ''; ?>">

<div class="faq-container">
    <div class="faq-header">
        <h1>Gyakori Kérdések</h1>
        <p>Minden, amit a Kalória Center használatáról tudni érdemes.</p>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            Hogyan számolja az app a napi kalóriakeretemet?
            <span class="icon">▼</span>
        </div>
        <div class="faq-answer">
            A rendszer a megadott korod, nemed, súlyod és magasságod alapján a Mifflin-St Jeor képletet használja az alapanyagcsere (BMR) kiszámításához, majd ezt korrigálja az aktivitási szinteddel.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            Mi a különbség a gramm és a darab alapú bevitel között?
            <span class="icon">▼</span>
        </div>
        <div class="faq-answer">
            A gramm alapú bevitel a pontos méréshez (pl. 150g csirkemell) ajánlott. A darab alapú bevitel olyan fix egységeknél hasznos, mint például egy tojás vagy egy szelet kenyér.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            Hogyan állíthatok be sötét módot?
            <span class="icon">▼</span>
        </div>
        <div class="faq-answer">
            A Dashboard jobb felső sarkában található profil menüben bármikor átválthatsz a "Sötét mód" opcióval. Az app megjegyzi a választásodat a következő belépésig.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            Biztonságban vannak az adataim?
            <span class="icon">▼</span>
        </div>
        <div class="faq-answer">
            Igen, a jelszavakat titkosított formában (hash) tároljuk az adatbázisban, és az adataidat soha nem adjuk ki harmadik félnek.
        </div>
    </div>

    <a href="dashboard.php" class="back-link">← Vissza a Dashboardra</a>
</div>

<div class="version-badge">HELP CENTER • V1.0.2</div>

<script>
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const item = question.parentElement;
            item.classList.toggle('active');
        });
    });

    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
</script>

</body>
</html>