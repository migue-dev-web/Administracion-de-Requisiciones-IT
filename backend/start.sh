#!/bin/bash
# Script para iniciar Laravel en Render

# Generar cache y limpiar cache de config, rutas y vistas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones automáticamente
php artisan migrate --force

# Iniciar servidor de Laravel
php artisan serve --host=0.0.0.0 --port=8000