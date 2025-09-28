# Imagen base con PHP, Composer y extensiones necesarias
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir directorio de trabajo
WORKDIR /app

# Copiar archivos
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Generar la clave de Laravel (Render usa envs)
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear

# Exponer puerto
EXPOSE 8000

# Comando para iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]