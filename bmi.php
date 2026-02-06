<?php
$height = $_POST['height'] / 100;
$weight = $_POST['weight'];

$bmi = $weight / ($height * $height);

echo "BMI érték: " . round($bmi, 2) . "<br>";

if ($bmi < 18.5) echo "Sovány";
elseif ($bmi < 25) echo "Normál testsúly";
elseif ($bmi < 30) echo "Túlsúly";
else echo "Elhízás";

echo "<br><small>Ez az eredmény tájékoztató jellegű.</small>";
echo "<br><a href='index.php'>Vissza</a>";
?>
