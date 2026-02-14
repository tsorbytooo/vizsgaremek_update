<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

session_start();
require 'database_connect.php';

$error = "";

if (isset($_POST['reg'])) {
    // Adatok tisztítása
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $height = (int)$_POST['height'];
    $weight = (int)$_POST['weight'];
    $age = (int)$_POST['age'];
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Jelszó ellenőrzés
    if ($password !== $confirm_password) {
        $error = "A két jelszó nem egyezik meg!";
    }
    // Jelszó hossz ellenőrzés (opcionális)
    elseif (strlen($password) < 6) {
        $error = "A jelszónak legalább 6 karakter hosszúnak kell lennie!";
    }
    else {
        // Ellenőrizzük, létezik-e már
        $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

        if (mysqli_num_rows($check_email) > 0) {
            $error = "Ez az e-mail cím már regisztrálva van!";
        } else {
            // Jelszó hash-elése
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (name, email, password, height, weight, age, gender, premium, theme) 
                    VALUES ('$name', '$email', '$hashed_password', $height, $weight, $age, '$gender', 0, 'dark')";

            if (mysqli_query($conn, $sql)) {
                
                // --- E-MAIL KÜLDÉS GMAIL-EL ---
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'kaloriacenter@gmail.com'; // <--- IDE ÍRD A SAJÁT CÍMED!
                    $mail->Password   = 'azig yhrm hqpm jgwu';      // <--- A JELSZÓ A KÉPEDRŐL
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;
                    $mail->CharSet    = 'UTF-8';

                    $mail->setFrom('noreply@kaloriacenter.hu', 'Caloria Center');
                    $mail->addAddress($email, $name);

                    $mail->isHTML(true);
                    $mail->Subject = 'Sikeres Regisztráció - Caloria Center';
                    $mail->Body    = "
                        <div style='font-family: Arial; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                            <h2 style='color: #10b981;'>Sikeres Regisztráció - Caloria Center</h2>
                            <p>Kedves $name!</p>
                            <p>Köszönjük, hogy minket választottál az egészséged megőrzéséhez!</p>
                            <p>A regisztrációd sikeresen lezajlott. Mostantól nyomon követheted a napi kalóriabeviteledet és a vízfogyasztásodat.</p>
                            <p>Sok sikert az eléréseidhez!</p>
                            <br>
                            <p>Üdvözlettel,<br>A Caloria Center csapata</p>
                        </div>";

                    $mail->send();
                } catch (Exception $e) {
                    // Itt nem állítjuk meg a folyamatot, ha az email nem megy el, a regisztráció már kész
                }

                header("Location: login.php?msg=success");
                exit();
            } else {
                $error = "Adatbázis hiba: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="theme-handler.js"></script>
    <style>
        :root {
            --primary: #5090d3;
            --bg-color: #0f1428;
            --text-main: #ffffff;
            --border-color: rgba(255, 255, 255, 0.1);
        }

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

        h2 { 
            color: white; 
            margin-bottom: 5px; 
            font-weight: 500;
        }

        .subtitle {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 25px;
        }

        .form-container {
            width: 100%;
            text-align: left;
        }

        label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            margin-bottom: 5px;
            margin-left: 5px;
        }

        /* --- Jelszó mutató stílusok --- */
        .password-container {
            position: relative;
            width: 100%;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #333;
            z-index: 10;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 5px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 12px;
        }

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

        button:hover { 
            background: #3b76b3;
            transform: translateY(-1px);
        }

        .footer-text {
            margin-top: 20px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
        }

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

        .password-hint {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: -8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Regisztráció</h2>
        <p class="subtitle">Hozd létre a profilod a pontos számításhoz!</p>

        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form action="" method="POST">

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
                    <div class="password-container">
                        <input type="password" name="password" id="password" required placeholder="********" minlength="6">
                        <i class="fa-solid fa-eye toggle-password" id="eye-main"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Jelszó megerősítése</label>
                    <div class="password-container">
                        <input type="password" name="confirm_password" id="confirm_password" required placeholder="********" minlength="6">
                        <i class="fa-solid fa-eye toggle-password" id="eye-confirm"></i>
                    </div>
                </div>
                
                <div class="password-hint" id="password-match-message"></div>

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

                <button type="submit" name="reg">Fiók létrehozása</button>
            </form>
        </div>

        <p class="footer-text">
            Van már fiókod? <br>
            <a href="login.php" class="white-link">Jelentkezz be!</a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const message = document.getElementById('password-match-message');
            
            const eyeMain = document.getElementById('eye-main');
            const eyeConfirm = document.getElementById('eye-confirm');

            // Jelszó láthatóság váltó funkció
            function setupPasswordToggle(inputEl, eyeEl) {
                eyeEl.addEventListener('click', function() {
                    if (inputEl.type === 'password') {
                        inputEl.type = 'text';
                        eyeEl.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        inputEl.type = 'password';
                        eyeEl.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            }

            setupPasswordToggle(password, eyeMain);
            setupPasswordToggle(confirmPassword, eyeConfirm);
            
            function checkPasswordMatch() {
                if (password.value && confirmPassword.value) {
                    if (password.value === confirmPassword.value) {
                        message.innerHTML = '✓ A jelszavak egyeznek';
                        message.style.color = '#4ade80';
                    } else {
                        message.innerHTML = '✗ A jelszavak nem egyeznek';
                        message.style.color = '#ff4d4d';
                    }
                } else { 
                    message.innerHTML = '';
                }
            }
            
            password.addEventListener('keyup', checkPasswordMatch);
            confirmPassword.addEventListener('keyup', checkPasswordMatch);
        });
    </script>

</body>
</html>