<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'database_connect.php'; // Itt hívjuk be a $conn változót

$message = "";

if (isset($_POST['belepes'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Felhasználó lekérése email alapján
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        // Jelszó ellenőrzése
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            
            // Ha sikeres, átirányítjuk a főoldalra
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "<b style='color:red;'>Hibás jelszó!</b>";
        }
    } else {
        $message = "<b style='color:red;'>Nincs ilyen email címmel regisztrált felhasználó!</b>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Bejelentkezés</h2>
    <?php echo $message; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email cím" required><br><br>
        <input type="password" name="password" placeholder="Jelszó" required><br><br>
        <button type="submit" name="belepes">Belépés</button>
    </form>
    <p>Még nincs fiókod? <a href="register.php">Regisztrálj itt!</a></p>
</body>
</html>

<script>
if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
}
</script>