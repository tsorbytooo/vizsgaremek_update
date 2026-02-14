#!/bin/bash

# Lépjünk a megfelelő könyvtárba
cd "$(dirname "$0")"

echo "🚀 Caloria Center indítása..."

# Hozzunk létre egy tiszta állapotot
echo "🧹 Tisztítás (régi konténerek leállítása)..."
docker-compose down

# Jogosultságok javítása (Linux/Mac esetén fontos lehet a .htaccess-hez)
chmod 644 .htaccess 2>/dev/null


# Konténerek újraépítése és indítása
echo "🏭 Konténerek építése és indítása..."
docker-compose up --build -d

# Várjunk egy kicsit, hogy az adatbázis elinduljon
echo "⏳ Várakozás az adatbázisra (15 mp)..."
sleep 15

# Ellenőrizzük az adatbázist és importáljuk, ha üres
echo "🗄️ Adatbázis ellenőrzése..."
TABLE_COUNT=$(docker exec vizsgaremek_update-db-1 mysql -u csorba -pcsorba caloria_center -e "SHOW TABLES;" | wc -l)

if [ "$TABLE_COUNT" -le 1 ]; then
    echo "📥 Adatbázis üres, SQL fájl importálása..."
    docker exec -i vizsgaremek_update-db-1 mysql -u csorba -pcsorba caloria_center < caloria_center.sql
    echo "✅ Adatbázis importálva."
else
    echo "✅ Adatbázis már tartalmaz adatokat."
fi

echo "🌐 Az alkalmazás elérhető: http://localhost:8080"
echo "✨ Kész!"
