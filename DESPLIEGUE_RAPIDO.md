# 🚀 Guía Rápida de Despliegue

## Opción 1: Script Automático (Recomendado)

### 1. Conectar al VPS
```bash
ssh root@tu-ip-del-vps
```

### 2. Subir el proyecto al VPS
```bash
# Desde tu Mac, comprimir el proyecto
cd /Users/alvaroriquelmevilla/Desktop/Cofrupa
tar -czf cofrupa.tar.gz --exclude='node_modules' --exclude='vendor' --exclude='.git' .

# Subir al VPS
scp cofrupa.tar.gz root@tu-ip-del-vps:/root/
```

### 3. Descomprimir en el VPS
```bash
# En el VPS
cd /var/www
sudo mkdir cofrupa
cd cofrupa
sudo tar -xzf /root/cofrupa.tar.gz
```

### 4. Ejecutar script de despliegue
```bash
cd /var/www/cofrupa
sudo bash deploy-vps.sh
```

**El script te pedirá:**
- Nombre de dominio (ej: cofrupa.com)
- Nombre de base de datos
- Usuario de base de datos
- Contraseña de base de datos
- Email para SSL

### 5. ¡Listo! 🎉
Visita: `https://tudominio.com`

---

## Opción 2: Manual Paso a Paso

Si prefieres hacerlo manualmente, sigue la guía completa en:
📄 **DESPLIEGUE_VPS_APACHE.md**

---

## 🔄 Para Actualizar el Sitio

```bash
ssh root@tu-ip-del-vps
cd /var/www/cofrupa

# Modo mantenimiento
php artisan down

# Subir nuevos archivos (desde tu Mac)
# scp archivo.php root@tu-ip-del-vps:/var/www/cofrupa/ruta/

# O si usas Git
git pull origin main

# Actualizar
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Quitar modo mantenimiento
php artisan up
```

---

## 📋 Checklist Pre-Despliegue

- [ ] Tienes acceso SSH al VPS
- [ ] Tu dominio apunta al IP del VPS (registros A y CNAME)
- [ ] Tienes las credenciales de tu base de datos listas
- [ ] Has configurado Google reCAPTCHA keys (opcional)

---

## 🛠️ Comandos Útiles

```bash
# Ver logs de errores
tail -f /var/www/cofrupa/storage/logs/laravel.log

# Reiniciar servicios
sudo systemctl restart apache2
sudo systemctl restart php8.2-fpm
sudo systemctl restart mysql

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Ver estado de servicios
sudo systemctl status apache2
sudo systemctl status mysql
```

---

## ⚠️ Problemas Comunes

### Error 500
```bash
# Verificar permisos
sudo chown -R www-data:www-data /var/www/cofrupa
sudo chmod -R 775 /var/www/cofrupa/storage
```

### CSS no carga
```bash
# Verificar APP_URL en .env
nano /var/www/cofrupa/.env
# Debe ser: APP_URL=https://tudominio.com

php artisan config:cache
```

### Base de datos no conecta
```bash
# Verificar credenciales en .env
nano /var/www/cofrupa/.env

# Probar conexión manual
mysql -u usuario -p nombre_db
```

---

## 📞 Acceso al Panel Admin

URL: `https://tudominio.com/admin/login`

**Credenciales por defecto:**
- Usuario: `admin@cofrupa.com`
- Contraseña: `Cofrupa2024!`

⚠️ **Cámbialas después del primer login!**

---

## 🎯 Tips

1. **Usa incógnito** para ver el sitio como cliente (sin modo edición)
2. **Configura backups** automáticos (ver DESPLIEGUE_VPS_APACHE.md)
3. **Monitorea los logs** regularmente
4. **Actualiza el sistema** mensualmente: `apt update && apt upgrade`

