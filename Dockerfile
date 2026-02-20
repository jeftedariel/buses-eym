FROM php:8.4-fpm-alpine AS base

RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    postgresql-dev \
    oniguruma-dev \
    icu-dev

RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        bcmath \
        mbstring \
        intl \
        opcache \
        pcntl \
        exif

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ---- Dependencias ----
FROM base AS deps

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

# ---- Build ----
FROM deps AS builder

COPY . .
RUN composer dump-autoload --optimize --no-dev
RUN npm run build

# ---- Producción ----
FROM base AS production

WORKDIR /var/www/html

COPY --from=builder /var/www/html /var/www/html

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["/start.sh"]
