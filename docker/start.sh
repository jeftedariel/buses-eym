#!/bin/sh
set -e

echo "🚀 Iniciando Laravel..."

# Permisos en bootstrap/cache
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/bootstrap/cache

# Esperar Postgres
echo "⏳ Esperando base de datos..."
MAX_TRIES=30
TRIES=0
until php -r "
    try {
        new PDO(
            'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );
        echo 'ok';
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null | grep -q ok; do
    TRIES=$((TRIES + 1))
    if [ $TRIES -ge $MAX_TRIES ]; then
        echo "❌ No se pudo conectar a la base de datos"
        exit 1
    fi
    echo "   Reintentando ($TRIES/$MAX_TRIES)..."
    sleep 3
done
echo "✅ Base de datos lista"

# Laravel bootstrap
echo "⚙️  Cacheando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "🗃️  Migraciones..."
php artisan migrate --force

# Arrancar servicios
echo "▶️  Arrancando PHP-FPM..."
php-fpm -D

echo "✅ App lista en :80"
nginx -g "daemon off;"
