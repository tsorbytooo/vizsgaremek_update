<?php
$servername = getenv('DB_HOST') ?: "db";
$username = getenv('DB_USER') ?: "csorba";
$password = getenv('DB_PASSWORD') ?: "csorba";
$dbname = getenv('DB_NAME') ?: "caloria_center";

// Kapcsolat létrehozása
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if (!$conn) {
    die("Sikertelen kapcsolódás: " . mysqli_connect_error());
}
// Beállítjuk a karakterkódolást, hogy ne legyenek bajok az ékezetekkel
mysqli_set_charset($conn, "utf8mb4");
?>