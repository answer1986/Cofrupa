# 🗄️ Crear y Configurar Base de Datos

## 🚨 ERROR: Aplicación en Blanco

Si tu aplicación muestra pantalla en blanco, es porque falta la base de datos. Sigue estos pasos:

---

## PASO 1: Conectar al VPS

```bash
ssh root@tu-ip-del-vps
```

---

## PASO 2: Crear Base de Datos en MySQL

```bash
# Entrar a MySQL como root
sudo mysql -u root -p
# (Si no te pide contraseña, solo: sudo mysql)
```

### Dentro de MySQL, ejecuta estos comandos:

```sql
-- Crear la base de datos
CREATE DATABASE cofrupa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario
CREATE USER 'cofrupa_user'@'localhost' IDENTIFIED BY 'TuContraseñaSegura123!';

-- Dar permisos
GRANT ALL PRIVILEGES ON cofrupa_db.* TO 'cofrupa_user'@'localhost';

-- Aplicar cambios
FLUSH PRIVILEGES;

-- Ver bases de datos creadas
SHOW DATABASES;

-- Salir
EXIT;
```

---

## PASO 3: Configurar .env

```bash
cd /var/www/cofrupa
sudo nano .env
```

**Busca y modifica estas líneas:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cofrupa_db
DB_USERNAME=cofrupa_user
DB_PASSWORD=TuContraseñaSegura123!
```

**Guardar:** `Ctrl+O` → `Enter` → `Ctrl+X`

---

## PASO 4: Generar APP_KEY (si no existe)

```bash
cd /var/www/cofrupa

# Verificar si existe APP_KEY en .env
grep APP_KEY .env

# Si está vacía o no existe, generar:
sudo -u www-data php artisan key:generate
```

---

## PASO 5: Ejecutar Migraciones

```bash
cd /var/www/cofrupa

# Limpiar cachés primero
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear

# Probar conexión a la base de datos
sudo -u www-data php artisan migrate:status

# Si da error, verifica las credenciales
# Si funciona, ejecutar migraciones:
sudo -u www-data php artisan migrate --force
```

---

## PASO 6: Verificar Permisos

```bash
# Permisos correctos para Laravel
sudo chown -R www-data:www-data /var/www/cofrupa
sudo chmod -R 755 /var/www/cofrupa
sudo chmod -R 775 /var/www/cofrupa/storage
sudo chmod -R 775 /var/www/cofrupa/bootstrap/cache
```

---

## PASO 7: Optimizar Laravel

```bash
cd /var/www/cofrupa

# Cachear configuración
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

---

## PASO 8: Reiniciar Servicios

```bash
sudo systemctl restart php8.2-fpm
sudo systemctl restart apache2
```

---

## PASO 9: Ver Logs si Aún No Funciona

```bash
# Ver últimos errores de Laravel
sudo tail -f /var/www/cofrupa/storage/logs/laravel.log

# Ver errores de Apache
sudo tail -f /var/log/apache2/cofrupa-error.log
```

---

## ✅ Verificar que Todo Funciona

1. **Visita tu sitio:** `https://tudominio.com`
2. **Debería cargar correctamente**
3. **Si sigue en blanco, revisa los logs arriba**

---

## 🐛 Troubleshooting Específico

### Error: "Access denied for user"
```bash
# Verificar credenciales en .env
cd /var/www/cofrupa
cat .env | grep DB_

# Probar conexión manualmente
mysql -u cofrupa_user -p
# Ingresa la contraseña, si entra, las credenciales están bien
# Escribe: USE cofrupa_db;
# Si funciona, la BD existe
```

### Error: "Base de datos no existe"
```bash
# Verificar bases de datos
sudo mysql -e "SHOW DATABASES;"

# Si no ves 'cofrupa_db', créala de nuevo
sudo mysql
CREATE DATABASE cofrupa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Pantalla en Blanco Persistente
```bash
# Habilitar debug TEMPORALMENTE
cd /var/www/cofrupa
sudo nano .env

# Cambiar:
APP_DEBUG=true

# Guardar y visitar el sitio
# Verás los errores específicos en pantalla

# DESPUÉS DE ARREGLAR, volver a:
APP_DEBUG=false
```

### Error 500 después de configurar DB
```bash
# Ver logs en tiempo real
sudo tail -f /var/www/cofrupa/storage/logs/laravel.log

# En otra terminal, recarga el sitio
# Los errores aparecerán en la terminal
```

---

## 📊 Comandos de Verificación Rápida

```bash
# 1. ¿MySQL está corriendo?
sudo systemctl status mysql

# 2. ¿La BD existe?
sudo mysql -e "SHOW DATABASES;" | grep cofrupa

# 3. ¿El usuario tiene acceso?
mysql -u cofrupa_user -p -e "USE cofrupa_db; SHOW TABLES;"

# 4. ¿APP_KEY existe?
grep APP_KEY /var/www/cofrupa/.env

# 5. ¿Permisos correctos?
ls -la /var/www/cofrupa/storage

# 6. ¿Apache está corriendo?
sudo systemctl status apache2

# 7. ¿PHP-FPM está corriendo?
sudo systemctl status php8.2-fpm
```

---

## 🎯 Script Rápido de Diagnóstico

```bash
#!/bin/bash
echo "🔍 Diagnóstico de Cofrupa"
echo "========================"
echo ""

echo "1. MySQL Status:"
systemctl status mysql | grep Active

echo ""
echo "2. Bases de datos:"
sudo mysql -e "SHOW DATABASES;" | grep cofrupa

echo ""
echo "3. Archivo .env existe:"
ls -la /var/www/cofrupa/.env

echo ""
echo "4. APP_KEY configurada:"
grep APP_KEY /var/www/cofrupa/.env | head -1

echo ""
echo "5. Permisos de storage:"
ls -ld /var/www/cofrupa/storage

echo ""
echo "6. Apache Status:"
systemctl status apache2 | grep Active

echo ""
echo "7. Últimos errores de Laravel:"
tail -5 /var/www/cofrupa/storage/logs/laravel.log 2>/dev/null || echo "No hay logs"

echo ""
echo "========================"
```

Copia este script, guárdalo como `diagnostico.sh` y ejecútalo con `bash diagnostico.sh`

---

## 💡 Solución Rápida Todo-en-Uno

```bash
# Ejecuta esto en el VPS si ya configuraste el .env correctamente:

cd /var/www/cofrupa

# Crear BD si no existe
sudo mysql -e "CREATE DATABASE IF NOT EXISTS cofrupa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Limpiar cachés
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear

# Generar key
sudo -u www-data php artisan key:generate --force

# Ejecutar migraciones
sudo -u www-data php artisan migrate --force

# Permisos
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage bootstrap/cache

# Cachear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Reiniciar
sudo systemctl restart php8.2-fpm apache2

echo "✅ Listo! Intenta cargar el sitio ahora."
```

---

## 📞 Si Nada Funciona

Envíame la salida de estos comandos:

```bash
# 1. Ver errores
sudo tail -20 /var/www/cofrupa/storage/logs/laravel.log

# 2. Ver config DB
cat /var/www/cofrupa/.env | grep DB_

# 3. Probar conexión
php -r "new PDO('mysql:host=127.0.0.1;dbname=cofrupa_db', 'cofrupa_user', 'tu_password');" && echo "✅ Conexión exitosa" || echo "❌ Error de conexión"
```






