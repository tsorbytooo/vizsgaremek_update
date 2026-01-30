<?php
session_start();
require 'database_connect.php';
$error = "";

if (isset($_POST['reg'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $height = (int)$_POST['height'];
    $weight = (int)$_POST['weight'];
    $age = (int)$_POST['age'];
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $error = "Ez az e-mail cím már regisztrálva van!";
    } else {
        $sql = "INSERT INTO users (name, email, password, height, weight, age, gender, premium) 
                VALUES ('$name', '$email', '$password', $height, $weight, $age, '$gender', 0)";
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?msg=success"); exit();
        } else {
            $error = "Hiba történt a mentés során!";
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
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card" style="max-width: 600px; margin: 40px auto;">
    <h2>Regisztráció</h2>
    <p class="subtitle" style="text-align:center; color: var(--text-muted);">Hozd létre a profilod a pontos számításhoz!</p>

    <?php if($error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label>Teljes név</label>
            <input type="text" name="name" required placeholder="Szöke Császár Bálint">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>E-mail cím</label>
                <input type="email" name="email" required placeholder="balint@sigma.hu">
            </div>
            <div class="form-group">
                <label>Jelszó</label>
                <input type="password" name="password" required placeholder="********">
            </div>
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

        <button type="submit" name="reg" class="btn-primary" style="margin-top:20px;">Fiók létrehozása</button>
    </form>
    <p style="text-align:center; margin-top:20px;">Van már fiókod? <a href="login.php" style="color:var(--primary); font-weight:bold;">Jelentkezz be!</a></p>
</div>

</body>
</html>