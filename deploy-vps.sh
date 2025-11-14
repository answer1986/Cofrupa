#!/bin/bash

# Script de Despliegue Automático para Cofrupa en VPS con Apache
# Uso: bash deploy-vps.sh

echo "🚀 Iniciando despliegue de Cofrupa en VPS..."
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para mensajes
msg_info() {
    echo -e "${GREEN}✓${NC} $1"
}

msg_error() {
    echo -e "${RED}✗${NC} $1"
}

msg_warn() {
    echo -e "${YELLOW}!${NC} $1"
}

# Verificar si estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    msg_error "Este script debe ejecutarse desde el directorio raíz del proyecto Laravel"
    exit 1
fi

msg_info "Directorio del proyecto verificado"

# Preguntar configuración
echo ""
echo "📝 Configuración del Despliegue"
echo "================================"
read -p "Nombre de dominio (ej: cofrupa.com): " DOMAIN
read -p "Base de datos (nombre): " DB_NAME
read -p "Usuario de base de datos: " DB_USER
read -sp "Contraseña de base de datos: " DB_PASS
echo ""
read -p "Email para SSL (Let's Encrypt): " SSL_EMAIL
echo ""

# Confirmar
echo ""
echo "Configuración:"
echo "  Dominio: $DOMAIN"
echo "  Base de datos: $DB_NAME"
echo "  Usuario DB: $DB_USER"
echo "  Email SSL: $SSL_EMAIL"
echo ""
read -p "¿Continuar con el despliegue? (s/n): " CONFIRM

if [ "$CONFIRM" != "s" ]; then
    msg_warn "Despliegue cancelado"
    exit 0
fi

echo ""
msg_info "Iniciando instalación..."

# Actualizar sistema
msg_info "Actualizando sistema..."
sudo apt update -qq && sudo apt upgrade -y -qq

# Instalar Apache
msg_info "Instalando Apache..."
sudo apt install apache2 -y -qq

# Agregar repositorio de PHP
msg_info "Agregando repositorio de PHP..."
sudo apt install software-properties-common -y -qq
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update -qq

# Instalar PHP y extensiones
msg_info "Instalando PHP 8.2 y extensiones..."
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml \
php8.2-bcmath php8.2-curl php8.2-gd php8.2-zip php8.2-intl php8.2-cli \
php8.2-common php8.2-tokenizer php8.2-fileinfo -y -qq

# Habilitar módulos de Apache
msg_info "Configurando módulos de Apache..."
sudo a2enmod rewrite > /dev/null 2>&1
sudo a2enmod proxy_fcgi setenvif > /dev/null 2>&1
sudo a2enconf php8.2-fpm > /dev/null 2>&1

# Instalar MySQL
msg_info "Instalando MySQL..."
sudo apt install mysql-server -y -qq

# Crear base de datos
msg_info "Configurando base de datos..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
sudo mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';" 2>/dev/null
sudo mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';" 2>/dev/null
sudo mysql -e "FLUSH PRIVILEGES;" 2>/dev/null

# Instalar Composer
if ! command -v composer &> /dev/null; then
    msg_info "Instalando Composer..."
    cd ~
    curl -sS https://getcomposer.org/installer -o composer-setup.php
    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet
    rm composer-setup.php
else
    msg_info "Composer ya está instalado"
fi

# Volver al directorio del proyecto
cd "$(dirname "$0")"

# Instalar dependencias
msg_info "Instalando dependencias de Laravel..."
sudo -u www-data composer install --optimize-autoloader --no-dev --quiet

# Configurar .env
msg_info "Configurando archivo .env..."
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Actualizar valores en .env
sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|APP_URL=.*|APP_URL=https://$DOMAIN|" .env
sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_NAME|" .env
sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USER|" .env
sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|" .env

# Generar key
msg_info "Generando key de aplicación..."
sudo -u www-data php artisan key:generate --force > /dev/null

# Ejecutar migraciones
msg_info "Ejecutando migraciones..."
sudo -u www-data php artisan migrate --force > /dev/null 2>&1

# Optimizar
msg_info "Optimizando aplicación..."
sudo -u www-data php artisan config:cache > /dev/null
sudo -u www-data php artisan route:cache > /dev/null
sudo -u www-data php artisan view:cache > /dev/null
sudo -u www-data php artisan storage:link > /dev/null 2>&1

# Configurar permisos
msg_info "Configurando permisos..."
sudo chown -R www-data:www-data "$(pwd)"
sudo chmod -R 755 "$(pwd)"
sudo chmod -R 775 "$(pwd)/storage"
sudo chmod -R 775 "$(pwd)/bootstrap/cache"

# Crear Virtual Host
msg_info "Configurando Virtual Host de Apache..."
VHOST_FILE="/etc/apache2/sites-available/cofrupa.conf"
sudo bash -c "cat > $VHOST_FILE" <<EOF
<VirtualHost *:80>
    ServerName $DOMAIN
    ServerAlias www.$DOMAIN
    ServerAdmin admin@$DOMAIN
    DocumentRoot $(pwd)/public

    <Directory $(pwd)/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/cofrupa-error.log
    CustomLog \${APACHE_LOG_DIR}/cofrupa-access.log combined

    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.2-fpm.sock|fcgi://localhost"
    </FilesMatch>
</VirtualHost>
EOF

# Habilitar sitio
sudo a2dissite 000-default.conf > /dev/null 2>&1
sudo a2ensite cofrupa.conf > /dev/null

# Reiniciar Apache
msg_info "Reiniciando Apache..."
sudo systemctl restart apache2

# Instalar Certbot para SSL
msg_info "Instalando Certbot para SSL..."
sudo apt install certbot python3-certbot-apache -y -qq

# Obtener certificado SSL
msg_info "Configurando SSL con Let's Encrypt..."
sudo certbot --apache -d "$DOMAIN" -d "www.$DOMAIN" \
    --non-interactive --agree-tos --email "$SSL_EMAIL" \
    --redirect > /dev/null 2>&1

if [ $? -eq 0 ]; then
    msg_info "Certificado SSL instalado correctamente"
else
    msg_warn "No se pudo instalar el certificado SSL automáticamente"
    msg_warn "Ejecuta manualmente: sudo certbot --apache -d $DOMAIN -d www.$DOMAIN"
fi

# Configurar firewall
msg_info "Configurando firewall..."
sudo ufw allow OpenSSH > /dev/null 2>&1
sudo ufw allow 'Apache Full' > /dev/null 2>&1
echo "y" | sudo ufw enable > /dev/null 2>&1

# Configurar PHP limits
msg_info "Optimizando configuración de PHP..."
sudo sed -i 's/upload_max_filesize = .*/upload_max_filesize = 10M/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/post_max_size = .*/post_max_size = 10M/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/memory_limit = .*/memory_limit = 256M/' /etc/php/8.2/fpm/php.ini
sudo systemctl restart php8.2-fpm

echo ""
echo "=========================================="
echo "✅ ¡Despliegue Completado Exitosamente!"
echo "=========================================="
echo ""
echo "🌐 Tu sitio está disponible en:"
echo "   https://$DOMAIN"
echo ""
echo "🔐 Panel de Administración:"
echo "   https://$DOMAIN/admin/login"
echo "   Usuario: admin@cofrupa.com"
echo "   Contraseña: Cofrupa2024!"
echo ""
echo "⚠️  IMPORTANTE:"
echo "   1. Cambia la contraseña de admin después del primer login"
echo "   2. Configura tus keys de Google reCAPTCHA en el .env"
echo ""
echo "📊 Comandos útiles:"
echo "   Ver logs: sudo tail -f storage/logs/laravel.log"
echo "   Reiniciar Apache: sudo systemctl restart apache2"
echo "   Limpiar caché: php artisan cache:clear"
echo ""
msg_info "¡Buen trabajo! 🎉"

