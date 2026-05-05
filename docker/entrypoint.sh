#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/html"
cd "$APP_DIR"

log() { echo "[entrypoint] $*"; }

# -----------------------------------------------------------------------------
# Ensure .env exists (env vars from compose override values inside)
# -----------------------------------------------------------------------------
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        log ".env not found. Copying from .env.example"
        cp .env.example .env
    else
        log ".env and .env.example are missing — creating empty .env"
        : > .env
    fi
fi

# -----------------------------------------------------------------------------
# Ensure writable dirs
# -----------------------------------------------------------------------------
mkdir -p storage/framework/{cache,sessions,testing,views} \
         storage/logs \
         storage/app/public \
         storage/app/private \
         bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# Discard any stale bootstrap caches from the host mount / prior builds
# (they may reference dev-only providers not installed in the image).
rm -f bootstrap/cache/config.php \
      bootstrap/cache/routes-v7.php \
      bootstrap/cache/events.php \
      bootstrap/cache/view.php

# -----------------------------------------------------------------------------
# Wait for PostgreSQL
# -----------------------------------------------------------------------------
if [ -n "${DB_HOST:-}" ] && [ "${DB_CONNECTION:-pgsql}" = "pgsql" ]; then
    log "Waiting for PostgreSQL at ${DB_HOST}:${DB_PORT:-5432}..."
    tries=0
    until pg_isready -h "${DB_HOST}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-postgres}" -q; do
        tries=$((tries + 1))
        if [ "$tries" -ge 60 ]; then
            log "PostgreSQL did not become ready in time"
            exit 1
        fi
        sleep 2
    done
    log "PostgreSQL is ready"
fi

# -----------------------------------------------------------------------------
# Ensure APP_KEY
#
# Compose injects APP_KEY (possibly empty) into the environment, which
# overrides the .env file when Laravel boots. If the incoming value is empty
# we generate a new key, write it to .env, and export it so every child
# process (php-fpm, queue worker) picks it up.
# -----------------------------------------------------------------------------
if [ -z "${APP_KEY:-}" ]; then
    if ! grep -qE '^APP_KEY=base64:' .env 2>/dev/null; then
        log "Generating APP_KEY"
        # Temporarily unset APP_KEY so key:generate treats it as missing.
        env -u APP_KEY php artisan key:generate --force --no-interaction
    fi
    APP_KEY="$(grep -E '^APP_KEY=' .env | head -n1 | cut -d= -f2-)"
    export APP_KEY
    log "APP_KEY loaded from .env"
fi

# -----------------------------------------------------------------------------
# Run Laravel bootstrap commands
# -----------------------------------------------------------------------------
log "Running database migrations"
php artisan migrate --force --no-interaction

# Storage symlink (idempotent)
if [ ! -L public/storage ]; then
    log "Creating storage symlink"
    php artisan storage:link --force || true
fi

log "Caching configuration, routes, views, events"
php artisan config:cache
php artisan route:cache || true
php artisan view:cache  || true
php artisan event:cache || true
php artisan icons:cache || true

# Filament post-install hooks (no-op if not needed)
php artisan filament:upgrade --no-interaction || true

# -----------------------------------------------------------------------------
# Optional: run seeders once (set RUN_SEEDERS=true)
# -----------------------------------------------------------------------------
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    log "Running seeders"
    php artisan db:seed --force --no-interaction || true
fi

log "Startup complete — handing off to: $*"
exec "$@"
