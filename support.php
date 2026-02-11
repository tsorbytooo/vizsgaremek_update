<?php
session_start();
require 'database_connect.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$success_msg = "";

if (isset($_POST['send_ticket'])) {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO support_tickets (user_id, subject, category, message) VALUES ($user_id, '$subject', '$category', '$message')";
    
    if (mysqli_query($conn, $sql)) {
        $success_msg = "Üzenet sikeresen elküldve! Hamarosan válaszolunk.";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support & Feedback</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #f4f7f6;">

<div class="card" style="max-width: 600px; margin: 50px auto; padding: 30px;">
    <h2 style="margin-bottom: 10px;">Kapcsolat & Visszajelzés</h2>
    <p style="color: var(--text-muted); margin-bottom: 25px;">Miben segíthetünk? Vagy van egy jó ötleted?</p>

    <?php if ($success_msg): ?>
        <div style="background: #f0fdf4; color: #10b981; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #10b981;">
            <?php echo $success_msg; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Kategória</label>
            <select name="category" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
                <option value="Support">Segítségre van szükségem</option>
                <option value="Feedback">Fejlesztési ötlet</option>
                <option value="Bug">Hibát találtam</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tárgy</label>
            <input type="text" name="subject" placeholder="Rövid összefoglaló" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Üzenet</label>
            <textarea name="message" rows="5" placeholder="Írd le részletesen..." required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;"></textarea>
        </div>

        <button type="submit" name="send_ticket" class="btn-primary" style="width: 100%; padding: 15px; font-size: 1rem;">Üzenet küldése</button>
        
        <a href="dashboard.php" style="display: block; text-align: center; margin-top: 20px; color: var(--primary); text-decoration: none; font-weight: bold;">← Vissza a Dashboardra</a>
    </form>
</div>

<div class="card" style="max-width: 600px; margin: 20px auto; padding: 20px;">
    <h3>Korábbi üzeneteid</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <?php
        $history = mysqli_query($conn, "SELECT * FROM support_tickets WHERE user_id = $user_id ORDER BY created_at DESC");
        if (mysqli_num_rows($history) == 0) echo "<tr><td style='color:gray'>Még nem küldtél üzenetet.</td></tr>";
        while ($row = mysqli_fetch_assoc($history)):
        ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px 0;">
                    <small style="color: #666;"><?php echo $row['created_at']; ?> [<?php echo $row['category']; ?>]</small><br>
                    <strong><?php echo htmlspecialchars($row['subject']); ?></strong>
                </td>
                <td style="text-align: right;">
                    <span style="font-size: 0.8rem; padding: 4px 8px; border-radius: 10px; background: <?php echo $row['status'] == 'Open' ? '#fff3cd' : '#d1e7dd'; ?>;">
                        <?php echo $row['status'] == 'Open' ? 'Folyamatban' : 'Lezárva'; ?>
                    </span>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>