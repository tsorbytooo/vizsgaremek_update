<?php
session_start();
require 'database_connect.php';

// BIZTONSÁG: Csak a 9-es ID-val rendelkező admin (Ádám) léphet be!
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 9) { 
    die("Hoppá! Ez a terület csak az adminisztrátornak (ID: 9) van fenntartva."); 
}

// TICKET LEZÁRÁSA - Frissítve az új fájlnévre
if (isset($_GET['close_id'])) {
    $close_id = (int)$_GET['close_id'];
    mysqli_query($conn, "UPDATE support_tickets SET status = 'Closed' WHERE id = $close_id");
    header("Location: admin.php"); exit();
}

// TICKET TÖRLÉSE - Frissítve az új fájlnévre
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM support_tickets WHERE id = $del_id");
    header("Location: admin.php"); exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin Support Panel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .badge { padding: 4px 8px; border-radius: 5px; font-size: 0.8rem; font-weight: bold; }
        .bg-bug { background: #fee2e2; color: #dc2626; }
        .bg-feed { background: #fef9c3; color: #ca8a04; }
        .bg-supp { background: #dbeafe; color: #2563eb; }
        .status-open { color: #059669; font-weight: bold; }
        .status-closed { color: #94a3b8; text-decoration: line-through; }
        
        /* Táblázat stílus a sötét módhoz igazítva */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; border-bottom: 1px solid #334155; text-align: left; }
        tr:hover { background: #2d3748; }
    </style>
</head>
<body style="background-color: #0f172a; color: #f8fafc; font-family: sans-serif;">

<div style="max-width: 1100px; margin: 40px auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; color: white;">
        <h1>🛡️ Admin Support Központ</h1>
        <div style="display: flex; gap: 15px;">
            <a href="dashboard.php" style="background: #334155; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">Vissza a Dashboardra</a>
        </div>
    </div>

    <div class="card-section" style="background: #1e293b; border-radius: 15px; padding: 25px; border: 1px solid #334155; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h3>Beérkezett üzenetek</h3>
        <table>
            <thead>
                <tr style="color: #94a3b8; font-size: 0.9rem; text-transform: uppercase;">
                    <th>User</th>
                    <th>Kategória</th>
                    <th>Tárgy & Üzenet</th>
                    <th>Dátum</th>
                    <th>Státusz</th>
                    <th style="text-align: right;">Művelet</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT s.*, u.name as user_name FROM support_tickets s 
                        JOIN users u ON s.user_id = u.id 
                        ORDER BY s.status DESC, s.created_at DESC";
                $res = mysqli_query($conn, $sql);
                
                if(mysqli_num_rows($res) == 0): ?>
                    <tr><td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">Nincs beérkező üzenet.</td></tr>
                <?php endif;

                while($row = mysqli_fetch_assoc($res)):
                    $cat_class = ($row['category'] == 'Bug') ? 'bg-bug' : (($row['category'] == 'Feedback') ? 'bg-feed' : 'bg-supp');
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($row['user_name']); ?></strong></td>
                    <td><span class="badge <?php echo $cat_class; ?>"><?php echo $row['category']; ?></span></td>
                    <td style="max-width: 350px;">
                        <div style="font-weight: bold; margin-bottom: 5px;"><?php echo htmlspecialchars($row['subject']); ?></div>
                        <div style="color: #94a3b8; font-size: 0.85rem; line-height: 1.4;"><?php echo nl2br(htmlspecialchars($row['message'])); ?></div>
                    </td>
                    <td><small style="color: #64748b;"><?php echo $row['created_at']; ?></small></td>
                    <td>
                        <span class="<?php echo $row['status'] == 'Open' ? 'status-open' : 'status-closed'; ?>">
                            <?php echo $row['status'] == 'Open' ? '● NYITOTT' : 'LEZÁRVA'; ?>
                        </span>
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <?php if($row['status'] == 'Open'): ?>
                            <a href="admin.php?close_id=<?php echo $row['id']; ?>" style="background: #10b981; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: bold; margin-right: 8px;">Lezárás</a>
                        <?php endif; ?>
                        <a href="admin.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Végleg törlöd ezt az üzenetet?')" style="color: #ef4444; text-decoration: none; font-size: 1.2rem;" title="Törlés">🗑️</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>