# DAFI Beton — imazh prodhimi për Render (PHP 8.3 + Laravel + Filament)
FROM php:8.3-cli

# Ekstensionet e PHP-së që i duhen Laravel + Filament + Postgres
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions pdo_pgsql pgsql zip gd bcmath intl mbstring exif pcntl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Instalo varësitë (cache i shtresës kur composer.* s'ndryshon)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# Kopjo aplikacionin e plotë
COPY . .
RUN composer dump-autoload --optimize --no-dev \
    && chmod -R 775 storage bootstrap/cache

# Në nisje: migrimet + të dhënat fillestare (idempotente), pastaj serveri.
# $PORT vendoset nga Render.
CMD php artisan migrate --force \
    && php artisan db:seed --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
