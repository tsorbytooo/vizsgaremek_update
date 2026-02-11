<?php
session_start();
require 'database_connect.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$show_success = false;

if (isset($_POST['complete_payment'])) {
    // Adatok begyűjtése az űrlapról
    $email = mysqli_real_escape_string($conn, $_POST['billing_email']);
    $address = mysqli_real_escape_string($conn, $_POST['billing_address']);
    $name = mysqli_real_escape_string($conn, $_POST['card_name']);

    // --- E-MAIL KÜLDÉS GMAIL-EL ---
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tamaski1111@gmail.com'; // <--- IDE ÍRD A SAJÁT CÍMED!
        $mail->Password   = 'qcrw jigf vumh zjlh';      // <--- A JELSZÓ A KÉPEDRŐL
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('noreply@kaloriacenter.hu', 'Kalória Center');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Sikeres Premium Előfizetés - Számla';
        $mail->Body    = "
            <div style='font-family: Arial; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                <h2 style='color: #10b981;'>Köszönjük a vásárlást, $name!</h2>
                <p>A prémium tagságod aktiválva lett.</p>
                <p><strong>Számlázási cím:</strong> $address</p>
                <p><strong>Összeg:</strong> 4 990 Ft</p>
                <br>
                <p>Üdvözlettel,<br>A Kalória Center csapata</p>
            </div>";

        $mail->send();

        // Ha az e-mail elment, frissítjük az adatbázist
        $update_sql = "UPDATE users SET premium = 1 WHERE id = $user_id";
        if (mysqli_query($conn, $update_sql)) {
            $show_success = true;
        }
    } catch (Exception $e) {
        $error_msg = "Hiba az e-mail küldésekor: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biztonságos Fizetés | Kalória Center</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .checkout-card { max-width: 550px; margin: 40px auto; padding: 30px; background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .success-box { text-align: center; padding: 20px; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9rem; }
        .input-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
    </style>
</head>
<body style="background-color: #f4f7f6;">

<?php if (!$show_success): ?>
    <div class="checkout-card">
        <h2 style="text-align: center; color: #2b2d42;">💳 Biztonságos Fizetés</h2>
        
        <?php if(isset($error_msg)) echo "<p style='color:red;'>$error_msg</p>"; ?>

        <form method="POST">
            <h4 style="margin-bottom: 10px; color: #4361ee;">1. Számlázási információk</h4>
            <div class="input-group">
                <label>E-mail cím</label>
                <input type="email" name="billing_email" placeholder="ide.kuldjuk@a.szamlat.hu" required>
            </div>
            <div class="input-group">
                <label>Számlázási cím</label>
                <input type="text" name="billing_address" placeholder="Irányítószám, Város, Utca, Házszám" required>
            </div>

            <h4 style="margin: 25px 0 10px 0; color: #4361ee;">2. Bankkártya adatok</h4>
            <div class="input-group">
                <label>Kártyabirtokos neve</label>
                <input type="text" name="card_name" placeholder="Minta János" required>
            </div>

            <div class="input-group">
                <label>Kártyaszám</label>
                <input type="text" name="card_number" placeholder="1234 5678 1234 5678" maxlength="19" required>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="input-group" style="flex: 1;">
                    <label>Lejárat</label>
                    <input type="text" name="exp" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="input-group" style="flex: 1;">
                    <label>CVC</label>
                    <input type="text" name="cvc" placeholder="123" maxlength="3" required>
                </div>
            </div>

            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <div style="display: flex; justify-content: space-between;">
                    <span>Fizetendő összeg:</span>
                    <strong>4 990 Ft</strong>
                </div>
            </div>

            <button type="submit" name="complete_payment" class="btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem; background: #10b981; border: none; color: white; border-radius: 8px; cursor: pointer;">
                Fizetés Bejezése
            </button>
            
            <a href="premium.php" style="display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; font-size: 0.9rem;">Mégse</a>
        </form>
    </div>

<?php else: ?>
    <div class="checkout-card" style="border-top: 5px solid #10b981;">
        <div class="success-box">
            <h1 style="font-size: 50px; margin: 0;">✅</h1>
            <h2 style="color: #10b981;">Sikeres vásárlás!</h2>
            <p>Köszönjük a bizalmadat! A számlát elküldtük az e-mail címedre.</p>
            
            <div style="margin: 30px 0;">
                <button onclick="window.print()" style="padding: 10px 20px; background: #374151; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    📄 Számla nyomtatása / Mentése PDF-ként
                </button>
            </div>

            <a href="dashboard.php" class="btn-primary" style="text-decoration: none; display: inline-block; padding: 12px 25px;">Vissza a Dashboardra</a>
        </div>
    </div>
<?php endif; ?>

</body>
</html>