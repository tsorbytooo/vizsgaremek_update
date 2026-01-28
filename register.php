<?php
require 'database_connect.php'; 
$message = "";

if (isset($_POST['regisztralas'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $height = (int)$_POST['height'];
    $weight = (int)$_POST['weight'];
    $age = (int)$_POST['age'];
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Ez az email már foglalt!";
    } else {
        $sql = "INSERT INTO users (name, email, password, height, weight, age, gender) 
                VALUES ('$name', '$email', '$pass', $height, $weight, $age, '$gender')";
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?success=1");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <h2>Regisztráció</h2>
        <?php if($message) echo "<p class='error'>$message</p>"; ?>
        
        <form action="register.php" method="POST">
            <div class="form-group">
                <label>Teljes név</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>E-mail cím</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Jelszó</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Magasság (cm)</label>
                    <input type="number" name="height" required>
                </div>
                <div class="form-group">
                    <label>Súly (kg)</label>
                    <input type="number" name="weight" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Életkor</label>
                    <input type="number" name="age" required>
                </div>
                <div class="form-group">
                    <label>Nem</label>
                    <select name="gender">
                        <option value="male">Férfi</option>
                        <option value="female">Nő</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="regisztralas">Fiók létrehozása</button>
        </form>
        <p class="footer-text">Van már fiókod? <a href="login.php">Jelentkezz be!</a></p>
    </div>
</body>
</html>