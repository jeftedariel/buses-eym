# syntax=docker/dockerfile:1.7

# =========================================================================
# Stage 1: Composer dependencies
# =========================================================================
FROM php:8.4-cli-alpine AS vendor

RUN apk add --no-cache git unzip libzip-dev icu-dev oniguruma-dev libpng-dev \
        postgresql-dev autoconf g++ make linux-headers \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        intl \
        zip \
        bcmath \
        pcntl \
        opcache

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

# Install without running scripts (artifacts not present yet)
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --prefer-dist \
        --no-scripts \
        --no-autoloader

COPY . .

# Ensure .env exists so artisan can boot during autoload-dump scripts
RUN cp -n .env.example .env || true

# Clear any stale bootstrap caches baked into the repo (they may reference
# dev-only providers like Laravel\Pail which aren't installed in prod).
RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php bootstrap/cache/config.php \
           bootstrap/cache/routes-v7.php bootstrap/cache/events.php

RUN composer dump-autoload --optimize --classmap-authoritative \
    && composer run-script post-autoload-dump --no-interaction || true


# =========================================================================
# Stage 2: Frontend assets
# =========================================================================
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN npm run build


# =========================================================================
# Stage 3: Runtime image (nginx + php-fpm + supervisor)
# =========================================================================
FROM php:8.4-fpm-alpine AS runtime

# --- System dependencies ---
RUN apk add --no-cache \
        nginx \
        supervisor \
        bash \
        curl \
        git \
        unzip \
        tini \
        icu-libs \
        libzip \
        libpng \
        libjpeg-turbo \
        freetype \
        oniguruma \
        postgresql-client \
        postgresql-libs \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        oniguruma-dev \
        postgresql-dev \
        linux-headers \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        intl \
        zip \
        bcmath \
        pcntl \
        gd \
        opcache \
        exif \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

# Copy composer binary for on-demand use
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# --- PHP configuration ---
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/zz-www.conf

# --- Nginx configuration ---
RUN rm -f /etc/nginx/http.d/default.conf
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/app.conf /etc/nginx/http.d/app.conf

# --- Supervisor ---
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# --- Application user ---
ARG APP_USER=www-data
ARG APP_UID=1000
RUN set -eux; \
    if getent passwd ${APP_USER} >/dev/null; then deluser ${APP_USER}; fi; \
    adduser -D -u ${APP_UID} -s /bin/bash ${APP_USER}

WORKDIR /var/www/html

# --- Copy application code ---
COPY --chown=${APP_USER}:${APP_USER} . /var/www/html
COPY --from=vendor --chown=${APP_USER}:${APP_USER} /app/vendor /var/www/html/vendor
COPY --from=assets --chown=${APP_USER}:${APP_USER} /app/public/build /var/www/html/public/build

# --- Prepare runtime directories ---
RUN mkdir -p storage/framework/{cache,sessions,testing,views} \
             storage/logs \
             storage/app/public \
             storage/app/private \
             bootstrap/cache \
             /var/log/supervisor \
             /run/nginx \
    && chown -R ${APP_USER}:${APP_USER} storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

# --- Entrypoint ---
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 7070

ENV APP_ENV=production \
    APP_DEBUG=false \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD curl -fsS http://127.0.0.1:7070/up || exit 1

ENTRYPOINT ["/sbin/tini", "--", "/usr/local/bin/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
