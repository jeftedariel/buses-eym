#!/bin/sh
set -e

if [ "$DB_CONNECTION" = "sqlite" ]; then
    mkdir -p /app/database
    touch /app/database/database.sqlite
    chmod 777 /app/database/database.sqlite
fi

php artisan migrate --force --no-interaction

exec php artisan octane:frankenphp \
    --host=0.0.0.0 \
    --port=${OCTANE_PORT:-80} \
    --workers=${OCTANE_WORKERS:-auto} \
    --max-requests=${OCTANE_MAX_REQUESTS:-500} \
    --https
