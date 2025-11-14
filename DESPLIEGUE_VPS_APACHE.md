# 🚀 Guía de Despliegue en VPS con Apache

## 📋 Requisitos Previos

- VPS con Ubuntu 20.04 o superior (puede ser DigitalOcean, AWS, Vultr, etc.)
- Acceso root o sudo al servidor
- Dominio apuntando al IP del VPS (opcional pero recomendado)

---

## 🔧 PASO 1: Conectar al VPS y Actualizar Sistema

```bash
# Conectar por SSH (reemplaza con tu IP)
ssh root@tu-ip-del-vps

# Actualizar el sistema
sudo apt update && sudo apt upgrade -y
```

---

## 📦 PASO 2: Instalar Apache, PHP 8.2 y Extensiones Necesarias

```bash
# Instalar Apache
sudo apt install apache2 -y

# Agregar repositorio de PHP
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Instalar PHP 8.2 y extensiones requeridas
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml \
php8.2-bcmath php8.2-curl php8.2-gd php8.2-zip php8.2-intl php8.2-cli \
php8.2-common php8.2-tokenizer php8.2-fileinfo -y

# Instalar módulos de Apache necesarios
sudo a2enmod rewrite
sudo a2enmod proxy_fcgi setenvif
sudo a2enconf php8.2-fpm
sudo systemctl restart apache2

# Verificar versión de PHP
php -v
```

---

## 🗄️ PASO 3: Instalar y Configurar MySQL

```bash
# Instalar MySQL
sudo apt install mysql-server -y

# Configurar MySQL de forma segura
sudo mysql_secure_installation
# Responde: Y a todo, elige contraseña fuerte para root

# Crear base de datos y usuario para Laravel
sudo mysql -u root -p
```

**Dentro de MySQL ejecuta:**

```sql
CREATE DATABASE cofrupa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cofrupa_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
GRANT ALL PRIVILEGES ON cofrupa_db.* TO 'cofrupa_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 📥 PASO 4: Instalar Composer

```bash
# Descargar e instalar Composer
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Verificar instalación
composer --version
```

---

## 📂 PASO 5: Subir y Configurar el Proyecto Laravel

### Opción A: Clonar desde Git (Recomendado)

```bash
# Si tu proyecto está en GitHub/GitLab
cd /var/www
sudo git clone https://github.com/tu-usuario/cofrupa.git
sudo chown -R www-data:www-data cofrupa
cd cofrupa
```

### Opción B: Subir archivos manualmente

```bash
# En tu computadora local, comprimir el proyecto (EXCLUIR node_modules y vendor)
# Desde tu Mac:
cd /Users/alvaroriquelmevilla/Desktop/Cofrupa
tar -czf cofrupa.tar.gz --exclude='node_modules' --exclude='vendor' --exclude='.git' .

# Subir al servidor usando SCP
scp cofrupa.tar.gz root@tu-ip-del-vps:/tmp/

# En el servidor VPS:
cd /var/www
sudo mkdir cofrupa
cd cofrupa
sudo tar -xzf /tmp/cofrupa.tar.gz
sudo chown -R www-data:www-data /var/www/cofrupa
```

---

## ⚙️ PASO 6: Configurar el Proyecto Laravel

```bash
cd /var/www/cofrupa

# Instalar dependencias de Composer
sudo -u www-data composer install --optimize-autoloader --no-dev

# Copiar archivo de entorno
sudo cp .env.example .env
sudo chown www-data:www-data .env

# Editar configuración
sudo nano .env
```

**Configurar el archivo `.env`:**

```env
APP_NAME=Cofrupa
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://tudominio.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cofrupa_db
DB_USERNAME=cofrupa_user
DB_PASSWORD=tu_contraseña_segura

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Google reCAPTCHA (configura tus keys)
RECAPTCHA_SITE_KEY=tu_site_key
RECAPTCHA_SECRET_KEY=tu_secret_key
```

**Guardar:** `Ctrl+O`, `Enter`, `Ctrl+X`

```bash
# Generar key de aplicación
sudo -u www-data php artisan key:generate

# Ejecutar migraciones
sudo -u www-data php artisan migrate --force

# Optimizar Laravel para producción
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Crear enlace simbólico para storage (si usas storage público)
sudo -u www-data php artisan storage:link
```

---

## 🔐 PASO 7: Configurar Permisos Correctos

```bash
# Permisos para directorios de Laravel
sudo chown -R www-data:www-data /var/www/cofrupa
sudo chmod -R 755 /var/www/cofrupa
sudo chmod -R 775 /var/www/cofrupa/storage
sudo chmod -R 775 /var/www/cofrupa/bootstrap/cache
```

---

## 🌐 PASO 8: Configurar Virtual Host de Apache

```bash
# Crear archivo de configuración del sitio
sudo nano /etc/apache2/sites-available/cofrupa.conf
```

**Contenido del archivo:**

```apache
<VirtualHost *:80>
    ServerName tudominio.com
    ServerAlias www.tudominio.com
    ServerAdmin admin@tudominio.com
    DocumentRoot /var/www/cofrupa/public

    <Directory /var/www/cofrupa/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Logs
    ErrorLog ${APACHE_LOG_DIR}/cofrupa-error.log
    CustomLog ${APACHE_LOG_DIR}/cofrupa-access.log combined

    # PHP-FPM
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.2-fpm.sock|fcgi://localhost"
    </FilesMatch>
</VirtualHost>
```

**Guardar:** `Ctrl+O`, `Enter`, `Ctrl+X`

```bash
# Habilitar el sitio y deshabilitar el default
sudo a2dissite 000-default.conf
sudo a2ensite cofrupa.conf

# Verificar configuración
sudo apache2ctl configtest
# Debe decir "Syntax OK"

# Reiniciar Apache
sudo systemctl restart apache2
```

---

## 🔒 PASO 9: Instalar SSL con Let's Encrypt (HTTPS)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache -y

# Obtener certificado SSL (reemplaza con tu dominio)
sudo certbot --apache -d tudominio.com -d www.tudominio.com

# Seguir las instrucciones en pantalla:
# 1. Ingresa tu email
# 2. Acepta términos
# 3. Elige opción 2 (Redirect HTTP to HTTPS)

# Verificar renovación automática
sudo certbot renew --dry-run
```

**Certbot configurará automáticamente tu Virtual Host para HTTPS.**

---

## 🔥 PASO 10: Configurar Firewall (Opcional pero Recomendado)

```bash
# Habilitar UFW
sudo ufw allow OpenSSH
sudo ufw allow 'Apache Full'
sudo ufw enable

# Verificar estado
sudo ufw status
```

---

## 📊 PASO 11: Optimizaciones Finales

### Aumentar límites de PHP

```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

**Modificar estos valores:**

```ini
upload_max_filesize = 10M
post_max_size = 10M
memory_limit = 256M
max_execution_time = 300
```

```bash
# Reiniciar PHP-FPM
sudo systemctl restart php8.2-fpm
```

### Configurar OPcache para mejor rendimiento

```bash
sudo nano /etc/php/8.2/fpm/conf.d/10-opcache.ini
```

**Agregar/modificar:**

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

```bash
sudo systemctl restart php8.2-fpm
sudo systemctl restart apache2
```

---

## ✅ PASO 12: Verificar Despliegue

1. **Visita tu sitio:** `https://tudominio.com`
2. **Accede al panel admin:** `https://tudominio.com/admin/login`
   - Usuario: `admin@cofrupa.com`
   - Contraseña: `Cofrupa2024!`

---

## 🔄 Actualizar el Sitio en el Futuro

```bash
cd /var/www/cofrupa

# Modo mantenimiento
sudo -u www-data php artisan down

# Si usas Git
sudo -u www-data git pull origin main

# Si subes archivos manualmente, sobrescribe los archivos

# Actualizar dependencias
sudo -u www-data composer install --no-dev --optimize-autoloader

# Ejecutar migraciones si hay cambios en BD
sudo -u www-data php artisan migrate --force

# Limpiar y reconstruir cachés
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Salir del modo mantenimiento
sudo -u www-data php artisan up
```

---

## 🐛 Troubleshooting Común

### Error 500 - Internal Server Error

```bash
# Ver logs de Laravel
sudo tail -f /var/www/cofrupa/storage/logs/laravel.log

# Ver logs de Apache
sudo tail -f /var/log/apache2/cofrupa-error.log
```

### Permisos incorrectos

```bash
sudo chown -R www-data:www-data /var/www/cofrupa
sudo chmod -R 755 /var/www/cofrupa
sudo chmod -R 775 /var/www/cofrupa/storage
sudo chmod -R 775 /var/www/cofrupa/bootstrap/cache
```

### CSS/JS no cargan

```bash
# Verificar APP_URL en .env
sudo nano /var/www/cofrupa/.env
# Debe ser: APP_URL=https://tudominio.com

# Limpiar cachés
cd /var/www/cofrupa
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear
```

### Base de datos no conecta

```bash
# Verificar que MySQL está corriendo
sudo systemctl status mysql

# Probar conexión
mysql -u cofrupa_user -p cofrupa_db
```

---

## 📧 Configurar Email (Opcional)

Si quieres que el formulario de contacto envíe emails:

```bash
sudo nano /var/www/cofrupa/.env
```

**Agregar configuración SMTP (ejemplo con Gmail):**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Cofrupa"
```

```bash
sudo -u www-data php artisan config:cache
```

---

## 🎯 Checklist Final

- [ ] Sitio accesible vía HTTPS
- [ ] Certificado SSL activo
- [ ] Panel admin funcional
- [ ] Imágenes se cargan correctamente
- [ ] Videos se reproducen
- [ ] Formulario de contacto funciona
- [ ] Mapa interactivo carga
- [ ] Multi-idioma funciona (ES/EN/ZH)
- [ ] reCAPTCHA configurado
- [ ] Logs de errores están vacíos
- [ ] Permisos correctos aplicados
- [ ] Backups configurados

---

## 💾 Configurar Backups Automáticos

```bash
# Crear script de backup
sudo nano /usr/local/bin/backup-cofrupa.sh
```

**Contenido:**

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/root/backups"
mkdir -p $BACKUP_DIR

# Backup de base de datos
mysqldump -u cofrupa_user -ptu_contraseña_segura cofrupa_db > $BACKUP_DIR/db_$DATE.sql

# Backup de archivos (storage y public)
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/cofrupa/storage /var/www/cofrupa/public/image

# Eliminar backups antiguos (mantener últimos 7 días)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completado: $DATE"
```

```bash
# Dar permisos de ejecución
sudo chmod +x /usr/local/bin/backup-cofrupa.sh

# Agregar a crontab (backup diario a las 2 AM)
sudo crontab -e
```

**Agregar línea:**

```cron
0 2 * * * /usr/local/bin/backup-cofrupa.sh >> /var/log/cofrupa-backup.log 2>&1
```

---

## 🎉 ¡Listo!

Tu sitio Cofrupa ya está desplegado profesionalmente en tu VPS con Apache.

**Recuerda:**
- Cambiar las credenciales de admin después del primer login
- Configurar backups regulares
- Monitorear logs de errores
- Mantener el sistema actualizado: `sudo apt update && sudo apt upgrade`

---

## 📞 Soporte

Si tienes problemas, revisa:
1. Logs de Laravel: `/var/www/cofrupa/storage/logs/laravel.log`
2. Logs de Apache: `/var/log/apache2/cofrupa-error.log`
3. Status de servicios: `sudo systemctl status apache2 php8.2-fpm mysql`

