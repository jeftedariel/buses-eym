FROM dunglas/frankenphp:1.3-php8.4-alpine

# 1. Dependencias esenciales
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    icu-dev \
    nodejs \
    npm \
    git \
    unzip \
    bash

# 2. Extensiones PHP para Filament v4
RUN install-php-extensions \
    gd \
    pcntl \
    bcmath \
    intl \
    zip \
    pdo_mysql \
    pdo_pgsql \
    opcache \
    redis

WORKDIR /app

# 3. Instalación de Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# 4. Copiar archivos (Copiamos todo primero)
COPY . .

# 5. Instalar dependencias y compilar
# Agregamos permisos de escritura antes de los comandos de Laravel
RUN chown -R www-data:www-data /app && \
    composer install --no-dev --optimize-autoloader && \
    npm install && \
    npm run build

# 6. Forzar permisos finales
RUN chmod -R 775 storage bootstrap/cache

# 7. Variables de entorno para FrankenPHP
ENV APP_ENV=production
ENV APP_DEBUG=false
# IMPORTANTE: Esta es la ruta que Octane/FrankenPHP busca
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

EXPOSE 80

# 8. Comando de inicio revisado
# Usamos 'php artisan octane:install --server=frankenphp' por si acaso no está el binario
ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80", "--workers=4"]
