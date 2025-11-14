# ✅ Checklist Completo de Despliegue

## 📅 ANTES DEL DESPLIEGUE

### 1. Preparación del Servidor
- [ ] Contratar VPS (DigitalOcean, Vultr, AWS, etc.)
- [ ] Obtener IP pública del VPS
- [ ] Configurar acceso SSH
- [ ] Anotar credenciales de acceso

### 2. Configuración del Dominio
- [ ] Comprar/tener dominio disponible
- [ ] Acceder al panel DNS del dominio
- [ ] Configurar registro A para `@` → IP del VPS
- [ ] Configurar registro A para `www` → IP del VPS
- [ ] Esperar propagación DNS (15 min - 24 hrs)
- [ ] Verificar: `dig +short tudominio.com`

### 3. Preparación del Proyecto
- [ ] Tener Google reCAPTCHA keys (opcional)
  - Site Key
  - Secret Key
- [ ] Decidir credenciales de base de datos
  - Nombre de DB: `cofrupa_db`
  - Usuario: `cofrupa_user`
  - Contraseña: (segura)
- [ ] Comprimir proyecto (sin node_modules, vendor, .git)

---

## 🚀 DURANTE EL DESPLIEGUE

### 4. Conexión y Subida
- [ ] Conectar al VPS: `ssh root@tu-ip`
- [ ] Subir proyecto al VPS
- [ ] Descomprimir en `/var/www/cofrupa`

### 5. Instalación Automática
- [ ] Ejecutar: `sudo bash deploy-vps.sh`
- [ ] Proporcionar datos cuando se soliciten:
  - Dominio
  - Nombre DB
  - Usuario DB
  - Contraseña DB
  - Email SSL
- [ ] Esperar finalización (5-10 min)

### 6. Verificación de Servicios
- [ ] Apache corriendo: `systemctl status apache2`
- [ ] PHP-FPM corriendo: `systemctl status php8.2-fpm`
- [ ] MySQL corriendo: `systemctl status mysql`
- [ ] Firewall activo: `ufw status`

---

## 🔍 DESPUÉS DEL DESPLIEGUE

### 7. Verificación del Sitio
- [ ] Visitar: `https://tudominio.com`
- [ ] Sitio carga correctamente
- [ ] Certificado SSL activo (candado verde)
- [ ] No hay errores 404 o 500

### 8. Pruebas de Funcionalidad
- [ ] Cambiar idioma (ES/EN/ZH) funciona
- [ ] Hero video se reproduce
- [ ] Sección "Quiénes Somos" video funciona
- [ ] Mapa interactivo carga y anima
- [ ] Logos de certificaciones visibles
- [ ] Reloj de Chile funciona y traduce
- [ ] Footer muestra correctamente

### 9. Formulario de Contacto
- [ ] Formulario se muestra correctamente
- [ ] Validaciones funcionan (email, longitud, etc.)
- [ ] Autocompletar país funciona
- [ ] Contador de caracteres funciona
- [ ] reCAPTCHA se muestra
- [ ] Botón "Enviar" muestra animación de barco
- [ ] Mensaje de éxito se muestra

### 10. Panel de Administración
- [ ] Acceder a: `https://tudominio.com/admin/login`
- [ ] Login con credenciales por defecto funciona
  - Email: `admin@cofrupa.com`
  - Contraseña: `Cofrupa2024!`
- [ ] Dashboard se carga correctamente
- [ ] Modo edición se activa en el frontend
- [ ] Lapicitos de edición visibles
- [ ] Editar texto funciona
- [ ] Cambiar imagen funciona (probar con <10MB)
- [ ] Cambios se reflejan en el frontend

### 11. Seguridad Post-Instalación
- [ ] **Cambiar contraseña de admin** (IMPORTANTE)
- [ ] Verificar que `.env` no sea accesible públicamente
- [ ] Confirmar que `APP_DEBUG=false` en .env
- [ ] Verificar que `APP_ENV=production` en .env
- [ ] Revisar logs: `tail -f storage/logs/laravel.log`

---

## 🔧 OPTIMIZACIÓN Y MANTENIMIENTO

### 12. Configuración Avanzada
- [ ] Configurar SMTP para emails (opcional)
- [ ] Configurar backups automáticos
- [ ] Configurar monitoreo de uptime (UptimeRobot, Pingdom)
- [ ] Configurar CDN (Cloudflare) para mayor velocidad
- [ ] Habilitar compresión gzip en Apache

### 13. SEO y Analytics
- [ ] Agregar Google Analytics
- [ ] Configurar Google Search Console
- [ ] Verificar meta tags
- [ ] Generar sitemap.xml
- [ ] Verificar robots.txt

### 14. Pruebas de Rendimiento
- [ ] Probar velocidad: https://pagespeed.web.dev/
- [ ] Probar en móviles reales
- [ ] Verificar tiempos de carga <3s
- [ ] Verificar imágenes optimizadas

---

## 📊 MONITOREO CONTINUO

### 15. Chequeos Semanales
- [ ] Revisar logs de errores
- [ ] Verificar espacio en disco: `df -h`
- [ ] Verificar uso de memoria: `free -h`
- [ ] Revisar mensajes de contacto (si se guardan en DB)

### 16. Mantenimiento Mensual
- [ ] Actualizar sistema: `apt update && apt upgrade`
- [ ] Revisar certificado SSL (auto-renueva en 60 días)
- [ ] Verificar backups funcionan
- [ ] Revisar logs de Apache: `/var/log/apache2/`

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### Error 500
```bash
# Ver logs
tail -f /var/www/cofrupa/storage/logs/laravel.log
tail -f /var/log/apache2/cofrupa-error.log

# Verificar permisos
sudo chown -R www-data:www-data /var/www/cofrupa
sudo chmod -R 775 /var/www/cofrupa/storage
```

### CSS/JS no cargan
```bash
# Verificar APP_URL
nano /var/www/cofrupa/.env

# Limpiar cachés
cd /var/www/cofrupa
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Sitio lento
```bash
# Optimizar Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar uso de recursos
top
htop
```

---

## 📱 CONTACTO DE EMERGENCIA

Si algo sale mal:

1. **Revisar logs** (Laravel y Apache)
2. **Verificar servicios** (Apache, PHP-FPM, MySQL)
3. **Revisar firewall** (UFW)
4. **Contactar soporte del VPS** si es problema de infraestructura
5. **Restaurar backup** si es necesario

---

## 🎉 CHECKLIST FINAL

- [ ] ✅ Sitio accesible vía HTTPS
- [ ] ✅ Todas las funcionalidades probadas
- [ ] ✅ Panel admin funcional
- [ ] ✅ Contraseña de admin cambiada
- [ ] ✅ Backups configurados
- [ ] ✅ Monitoreo activo
- [ ] ✅ Cliente puede editar contenido
- [ ] ✅ "Desarrollo hecho por R3Q" NO es editable
- [ ] ✅ Documentación entregada al cliente

---

## 📝 NOTAS

Fecha de despliegue: _______________

IP del VPS: _______________

Dominio: _______________

Proveedor VPS: _______________

Proveedor Dominio: _______________

Observaciones:
_________________________________
_________________________________
_________________________________

---

**¡Felicitaciones! Has desplegado exitosamente Cofrupa. 🎊**

