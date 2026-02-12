<?php
// --- SESSION ÉS ADATBÁZIS BLOKK --- 
session_start(); // Elindítja a PHP sessiont, hogy a felhasználó adatait (pl. ID, név) tárolhassuk
require 'database_connect.php'; // Betölti az adatbázis kapcsolatot biztosító fájlt
$message = ""; // Inicializáljuk az üzenet változót, amit hibaüzenetekhez használunk

// --- BEJELENTKEZÉS FELDOLGOZÁSA --- 
if (isset($_POST['belepes'])) { // Ellenőrzi, hogy a belépés gomb meg lett-e nyomva
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Biztonságosan kezeli az emailt SQL injekció ellen
    $password = $_POST['password']; // Mentjük a jelszót változóba
    $sql = "SELECT * FROM users WHERE email = '$email'"; // Lekérdezés az adatbázisból a felhasználó email címe alapján
    $result = mysqli_query($conn, $sql); // Lefuttatjuk a lekérdezést

    // --- FELHASZNÁLÓ ELLENŐRZÉSE --- 
    if ($row = mysqli_fetch_assoc($result)) { // Ha van találat, asszociatív tömbként betöltjük
        if (password_verify($password, $row['password'])) { // Ellenőrizzük a jelszó hash-ét
            $_SESSION['user_id'] = $row['id']; // Mentjük a felhasználó ID-ját sessionbe
            $_SESSION['user_name'] = $row['name']; // Mentjük a felhasználó nevét sessionbe
            header("Location: dashboard.php"); // Átirányítás a dashboard oldalra
            exit(); // A script futásának leállítása
        } else {
            $message = "<p class='error'>Hibás jelszó!</p>"; // Hibás jelszó esetén hibaüzenet
        }
    } else {
        $message = "<p class='error'>Nincs ilyen email!</p>"; // Ha az email nem található az adatbázisban
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <!-- --- META ÉS CÍM BLOKK --- -->
    <meta charset="UTF-8"> <!-- Karakterkódolás -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Reszponzív beállítás mobilra -->
    <title>Bejelentkezés</title> <!-- Oldal címe -->
    <script src="theme-handler.js"></script>

    <!-- --- STÍLUSOK BLOKK --- -->
    <style>
        /* --- SZÍNVARIÁNSOK --- */
        :root {
            --primary: #5090d3;
            --bg-color: #0f1428;
            --text-main: #ffffff;
            --border-color: rgba(255, 255, 255, 0.1);
        }

        /* --- BODY BLOKK --- */
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle, #1e2a5e 0%, #0f1428 100%);
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        /* --- LOGIN KÁRTYA BLOKK --- */
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 30px;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            width: 300px; /* Kártya szélessége */
            text-align: center;
        }

        h2 { 
            color: white; 
            margin-bottom: 25px; 
            font-weight: 500;
        }

        .form-container {
            width: 90%;
            margin: 0 auto;
        }

        /* --- INPUT BLOKK --- */
        input {
            width: 100%;
            padding: 10px 12px; 
            margin-bottom: 15px;
            border-radius: 10px;
            border: none;
            outline: none;
            font-size: 14px;
            box-sizing: border-box; /* Megakadályozza, hogy a box széteszen */
            background: rgba(255, 255, 255, 0.9);
        }

        /* --- GOMB BLOKK --- */
        button {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: var(--primary);
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 5px;
        }

        button:hover { 
            background: #3b76b3;
            transform: translateY(-1px);
        }

        /* --- LÁBLÉC BLOKK --- */
        .footer-text {
            margin-top: 20px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Fehérített regisztrációs link */
        .white-link {
            color: #ffffff !important;
            font-weight: bold;
            text-decoration: none;
            border-bottom: 1px solid #ffffff;
        }

        .white-link:hover {
            color: var(--primary) !important;
            border-bottom-color: var(--primary);
        }

        /* --- HIBAÜZENET BLOKK --- */
        .error { 
            color: #ff4d4d; 
            font-size: 13px; 
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- --- LOGIN KÁRTYA HTML BLOKK --- -->
    <div class="login-card">
        <h2>Bejelentkezés</h2>
        
        <?php if($message != "") echo $message; ?> <!-- Hibák megjelenítése, ha vannak -->
        
        <div class="form-container">
            <form method="POST">
                <input type="email" name="email" placeholder="Email cím" required>
                <input type="password" name="password" placeholder="Jelszó" required>
                <button type="submit" name="belepes">Belépés</button>
            </form>
        </div>
        
        <p class="footer-text">
            Még nincs fiókod? <br>
            <a href="register.php" class="white-link">Regisztrálj itt!</a>
        </p>
    </div>

</body>
</html>