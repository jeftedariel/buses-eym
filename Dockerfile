# =============================================================================
# Stage 1: Composer
# =============================================================================
FROM composer:2 AS composer-builder

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts --ignore-platform-reqs

# =============================================================================
# Stage 2: Node.js
# =============================================================================
FROM node:20-alpine AS node-builder

WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci --frozen-lockfile
COPY . .
COPY --from=composer-builder /app/vendor /app/vendor
RUN npm run build

# =============================================================================
# Stage 3: FrankenPHP
# =============================================================================
FROM dunglas/frankenphp

RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    pgsql \
    redis \
    zip \
    bcmath \
    gd \
    opcache \
    intl

COPY . /app
COPY --from=composer-builder /app/vendor /app/vendor
COPY --from=node-builder /app/public/build /app/public/build

WORKDIR /app

RUN mkdir -p bootstrap/cache storage/logs storage/framework/cache \
    storage/framework/sessions storage/framework/views \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
