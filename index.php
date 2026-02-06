<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Caloria Center</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #f6f9fc, #e9eef5);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        h2 {
            color: #34495e;
            margin-top: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .checkbox-group label {
            font-weight: normal;
            margin-top: 8px;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #2980b9;
        }

        hr {
            margin: 40px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🍔 Caloria Center</h1>
    <p style="text-align:center; color:#666;">
        Gyorséttermek összehasonlítása és BMI kalkulátor
    </p>

    <h2>⚖️ BMI kalkulátor</h2>
    <form action="bmi.php" method="post">
        <label>Magasság (cm)</label>
        <input type="number" name="height" required>

        <label>Testsúly (kg)</label>
        <input type="number" name="weight" required>

        <button type="submit">BMI számítás</button>
    </form>

    <hr>

    <h2>🍟 Gyorséttermek összehasonlítása</h2>
    <form action="foods.php" method="post" class="checkbox-group">
        <label><input type="checkbox" name="restaurants[]" value="McDonalds"> McDonald's</label>
        <label><input type="checkbox" name="restaurants[]" value="BurgerKing"> Burger King</label>
        <label><input type="checkbox" name="restaurants[]" value="KFC"> KFC</label>
        <label><input type="checkbox" name="restaurants[]" value="Subway"> Subway</label>

        <button type="submit">Összehasonlítás</button>
    </form>

    <footer>
        © 2026 Caloria Center – Vizsgaremek
    </footer>
</div>

</body>
</html>
