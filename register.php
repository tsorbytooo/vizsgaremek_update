<?php
session_start();
require 'database_connect.php';

$error = "";

if (isset($_POST['reg'])) {
    // Adatok tisztítása
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $height = (int)$_POST['height'];
    $weight = (int)$_POST['weight'];
    $age = (int)$_POST['age'];
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Ellenőrizzük, létezik-e már
    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

    if (mysqli_num_rows($check_email) > 0) {
        $error = "Ez az e-mail cím már regisztrálva van!";
    } else {
        // AZ SQL FÁJLOD ALAPJÁN EZ A PONTOS MEZŐSORREND ÉS LISTA
        // Hozzáadtuk a 'premium' (0) és a 'theme' ('dark') értékeket is
        $sql = "INSERT INTO users (name, email, password, height, weight, age, gender, premium, theme) 
                VALUES ('$name', '$email', '$password', $height, $weight, $age, '$gender', 0, 'dark')";

        if (mysqli_query($conn, $sql)) {
            // Ha sikerült a mentés, CSAK AKKOR irányítunk át
            header("Location: login.php?msg=success");
            exit();
        } else {
            // Ha itt hiba van, azt KI KELL ÍRNIA a képernyőre!
            $error = "Adatbázis hiba: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <!-- Karakterkódolás beállítása -->
    <meta charset="UTF-8">

    <!-- Reszponzív megjelenítés mobil eszközökön -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Oldal címe -->
    <title>Regisztráció</title>

    <script src="theme-handler.js"></script>

    <style>
        /* 
            CSS változók definiálása az egységes színkezeléshez 
        */
        :root {
            --primary: #5090d3;
            --bg-color: #0f1428;
            --text-main: #ffffff;
            --border-color: rgba(255, 255, 255, 0.1);
        }

        /* Oldal alapstílusa */
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle, #1e2a5e 0%, #0f1428 100%);
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            box-sizing: border-box;
        }

        /* A regisztrációs kártya megjelenése */
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 30px;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        /* Főcím stílusa */
        h2 { 
            color: white; 
            margin-bottom: 5px; 
            font-weight: 500;
        }

        /* Alcím stílusa */
        .subtitle {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 25px;
        }

        /* Űrlap konténer */
        .form-container {
            width: 100%;
            text-align: left;
        }

        /* Címkék stílusa */
        label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            margin-bottom: 5px;
            margin-left: 5px;
        }

        /* Kétoszlopos sor */
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 5px;
        }

        /* Űrlap mezők csoportja */
        .form-group {
            flex: 1;
            margin-bottom: 12px;
        }

        /* Input és select mezők stílusa */
        input, select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: none;
            outline: none;
            font-size: 14px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
        }

        /* Gomb stílusa */
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
            margin-top: 15px;
            font-size: 16px;
        }

        /* Gomb hover állapot */
        button:hover { 
            background: #3b76b3;
            transform: translateY(-1px);
        }

        /* Lábléc szöveg */
        .footer-text {
            margin-top: 20px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Link stílus */
        .white-link {
            color: #ffffff !important;
            font-weight: bold;
            text-decoration: none;
            border-bottom: 1px solid #ffffff;
        }

        /* Link hover állapot */
        .white-link:hover {
            color: var(--primary) !important;
            border-bottom-color: var(--primary);
        }

        /* Hibaüzenet megjelenése */
        .error-msg { 
            background: rgba(231, 29, 54, 0.2);
            color: #ff4d4d;
            padding: 10px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid rgba(231, 29, 54, 0.3);
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Regisztráció</h2>
        <p class="subtitle">Hozd létre a profilod a pontos számításhoz!</p>

        <!-- Ha van hibaüzenet, itt jelenik meg -->
        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <!-- Regisztrációs űrlap -->
            <form action="register.php" method="POST">

                <div class="form-group">
                    <label>Teljes név</label>
                    <input type="text" name="name" required placeholder="Példa Péter">
                </div>

                <div class="form-group">
                    <label>E-mail cím</label>
                    <input type="email" name="email" required placeholder="emailcim@gmail.com">
                </div>
                
                <div class="form-group">
                    <label>Jelszó</label>
                    <input type="password" name="password" required placeholder="********">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Magasság (cm)</label>
                        <input type="number" name="height" required placeholder="185">
                    </div>
                    <div class="form-group">
                        <label>Súly (kg)</label>
                        <input type="number" name="weight" required placeholder="95">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Életkor</label>
                        <input type="number" name="age" required placeholder="24">
                    </div>
                    <div class="form-group">
                        <label>Nem</label>
                        <select name="gender" required>
                            <option value="male">Férfi</option>
                            <option value="female">Nő</option>
                        </select>
                    </div>
                </div>

                <!-- Regisztrációs gomb -->
                <button type="submit" name="reg">Fiók létrehozása</button>
            </form>
        </div>

        <p class="footer-text">
            Van már fiókod? <br>
            <a href="login.php" class="white-link">Jelentkezz be!</a>
        </p>
    </div>

</body>
</html>