# Usamos la imagen oficial de FrankenPHP con PHP 8.4 sobre Alpine para ligereza
FROM dunglas/frankenphp:1.3-php8.4-alpine

# 1. Instalar dependencias del sistema y herramientas de compilación
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    icu-dev \
    nodejs \
    npm \
    git \
    unzip \
    bash

# 2. Instalar extensiones de PHP necesarias para Filament v4 y Laravel 12
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

# 3. Configurar directorio de trabajo
WORKDIR /app

# 4. Copiar archivos y preparar Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer
COPY . .

# 5. Instalación de dependencias (PHP y JS)
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 6. Permisos correctos para el servidor (FrankenPHP usa el usuario root por defecto en Alpine o 'www-data')
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# 7. Variables de entorno de ejecución
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# 8. Exponer el puerto que configuraste en Coolify
EXPOSE 80

# 9. Comando de inicio: Usamos el Worker Mode para máximo rendimiento en Laravel 12
ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80"]
