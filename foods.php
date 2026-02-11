<?php
require_once "db.php";

if (!isset($_POST['restaurants']) || count($_POST['restaurants']) == 0) {
    die("<p style='text-align:center;'>Nem választottál ki éttermet! <a href='index.php'>Vissza</a></p>");
}

$restaurants = $_POST['restaurants'];
$placeholders = implode(",", array_fill(0, count($restaurants), "?"));

$sql = "SELECT * FROM foods WHERE restaurant IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($restaurants)), ...$restaurants);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Összehasonlítás</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th {
            background: #3498db;
            color: white;
            padding: 12px;
        }

        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #3498db;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
        }

        .back:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🍔 Gyorséttermek összehasonlítása</h1>

    <table>
        <tr>
            <th>Étterem</th>
            <th>Étel</th>
            <th>Ár (Ft)</th>
            <th>Kalória (kcal)</th>
            <th>Fehérje (g)</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['restaurant']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['price'] ?></td>
            <td><?= $row['calories'] ?></td>
            <td><?= $row['protein'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a class="back" href="index.php">⬅ Vissza a főoldalra</a>
</div>

</body>
</html>
