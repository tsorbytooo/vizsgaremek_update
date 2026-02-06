<?php
$conn = new mysqli("localhost", "root", "", "caloriacenter");
if ($conn->connect_error) {
    die("Adatbázis hiba!");
}
?>
