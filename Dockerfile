FROM php:8.2-apache

# 1. Rendszerfüggőségek és PHP kiterjesztések telepítése (egy lépésben a hatékonyságért)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip mysqli \
    && docker-php-ext-enable mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# 2. Composer átemelése a hivatalos image-ből
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Apache beállítások
RUN a2enmod rewrite

# 4. Munkakönyvtár beállítása
WORKDIR /var/www/html

# 5. Projekt fájlok másolása
COPY . /var/www/html/

# 6. Függőségek telepítése
RUN composer install --no-interaction --optimize-autoloader

# 7. Jogosultságok beállítása a webszerver számára
RUN chown -R www-data:www-data /var/www/html