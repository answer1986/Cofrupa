#!/bin/bash

# Script para configurar Cofrupa con Cloudflare Tunnel
# Uso: ./setup-cloudflare-tunnel.sh [URL_DEL_TUNNEL]

echo "🚀 Configurando Cofrupa para Cloudflare Tunnel..."

# Si se proporciona URL, actualizar el .env
if [ ! -z "$1" ]; then
    echo "📝 Actualizando APP_URL a: $1"
    
    # Hacer backup del .env
    cp .env .env.backup
    
    # Actualizar APP_URL en .env
    if grep -q "^APP_URL=" .env; then
        sed -i '' "s|^APP_URL=.*|APP_URL=$1|" .env
    else
        echo "APP_URL=$1" >> .env
    fi
    
    echo "✅ .env actualizado"
fi

# Limpiar cachés
echo "🧹 Limpiando cachés..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimizar para producción
echo "⚡ Optimizando..."
php artisan config:cache
php artisan route:cache

echo ""
echo "✅ Configuración completa!"
echo ""
echo "📋 Pasos siguientes:"
echo "1. Inicia tu servidor: php artisan serve"
echo "2. En otra terminal, ejecuta: cloudflared tunnel --url http://localhost:8000"
echo "3. Copia la URL del tunnel (ejemplo: https://xxx.trycloudflare.com)"
echo "4. Actualiza APP_URL ejecutando: ./setup-cloudflare-tunnel.sh https://xxx.trycloudflare.com"
echo ""
echo "🌐 URL actual configurada:"
php artisan tinker --execute="echo config('app.url');"
echo ""

