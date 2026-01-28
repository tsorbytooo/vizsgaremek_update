<?php
$servername = "localhost";
$username = "csorba";
$password = "csorba";
$dbname = "caloria_center";

// Kapcsolat létrehozása
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if (!$conn) {
    die("Sikertelen kapcsolódás: " . mysqli_connect_error());
}
// Beállítjuk a karakterkódolást, hogy ne legyenek bajok az ékezetekkel
mysqli_set_charset($conn, "utf8mb4");
?>