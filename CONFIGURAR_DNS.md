# 🌐 Configurar DNS del Dominio

Antes de desplegar, debes configurar tu dominio para que apunte al VPS.

---

## 📍 Paso 1: Obtener IP del VPS

```bash
# Conecta a tu VPS y obtén su IP pública
curl ifconfig.me
```

Anota esta IP (ejemplo: `123.45.67.89`)

---

## 🔧 Paso 2: Configurar Registros DNS

Ve al panel de control de tu proveedor de dominio (GoDaddy, Namecheap, etc.) y configura estos registros:

### Registro A (Principal)
```
Tipo: A
Nombre/Host: @
Valor/Dirección: TU-IP-DEL-VPS
TTL: 3600 (o automático)
```

### Registro A (Subdominio www)
```
Tipo: A
Nombre/Host: www
Valor/Dirección: TU-IP-DEL-VPS
TTL: 3600 (o automático)
```

### Ejemplo Visual:
```
┌─────────┬──────────┬─────────────────┬──────┐
│  Tipo   │   Host   │     Valor       │ TTL  │
├─────────┼──────────┼─────────────────┼──────┤
│    A    │    @     │  123.45.67.89   │ 3600 │
│    A    │   www    │  123.45.67.89   │ 3600 │
└─────────┴──────────┴─────────────────┴──────┘
```

---

## 🕐 Paso 3: Esperar Propagación

La propagación DNS puede tardar:
- **Mínimo:** 5-15 minutos
- **Normal:** 1-4 horas
- **Máximo:** 24-48 horas

### Verificar Propagación

Desde tu Mac, puedes verificar:

```bash
# Verificar registro A
dig +short tudominio.com

# Verificar con diferentes DNS
nslookup tudominio.com 8.8.8.8
nslookup tudominio.com 1.1.1.1

# Verificar propagación global
# Visita: https://www.whatsmydns.net/
```

---

## ✅ Confirmar que Funciona

Cuando la propagación esté completa:

```bash
# Debería devolver la IP de tu VPS
ping tudominio.com
```

---

## 📝 Ejemplos según Proveedores

### GoDaddy
1. Inicia sesión en GoDaddy
2. Ve a "Mis Productos" → "Dominios"
3. Click en "DNS" junto a tu dominio
4. Busca sección "Registros"
5. Edita/Agrega registros tipo A

### Namecheap
1. Inicia sesión en Namecheap
2. Ve a "Domain List" → Click en "Manage"
3. Tab "Advanced DNS"
4. "Add New Record" → Tipo A

### Cloudflare (si usas sus DNS)
1. Inicia sesión en Cloudflare
2. Selecciona tu dominio
3. Ve a "DNS" en el menú lateral
4. "Add Record" → Tipo A
5. ⚠️ **Importante:** Desactiva el proxy (nube gris) durante la configuración inicial

---

## 🔒 Configuración SSL Adicional

Si usas Cloudflare:

1. **SSL/TLS:** Configura en modo "Full (strict)"
2. **Always Use HTTPS:** Activar
3. **Automatic HTTPS Rewrites:** Activar
4. **Min TLS Version:** TLS 1.2

---

## 🎯 Checklist DNS

- [ ] Registro A para `@` apuntando al VPS
- [ ] Registro A para `www` apuntando al VPS
- [ ] Esperé al menos 30 minutos desde la configuración
- [ ] `ping tudominio.com` devuelve la IP correcta
- [ ] `dig tudominio.com` devuelve la IP correcta
- [ ] Puedo visitar `http://tudominio.com` (aunque sin SSL aún)

---

## 💡 Tips

1. **Configura DNS ANTES de ejecutar Certbot** en el VPS
2. Si cambias de VPS, actualiza los registros A con la nueva IP
3. Guarda las configuraciones DNS por si necesitas revertir
4. Algunos proveedores tienen TTL caché, si cambias algo espera el TTL completo

---

## ⚠️ Problemas Comunes

### No puedo acceder al sitio después de horas
```bash
# Verificar que Apache esté corriendo en el VPS
ssh root@tu-ip-del-vps
sudo systemctl status apache2

# Verificar firewall
sudo ufw status
# Asegúrate que puerto 80 y 443 estén abiertos
```

### "This site can't be reached"
- DNS aún no propagado → Espera más tiempo
- Firewall del VPS bloqueando → Revisa UFW
- Apache no corriendo → `systemctl restart apache2`

### Certificado SSL no se instala
```bash
# Asegúrate que el DNS YA apunte al VPS
dig +short tudominio.com

# Debe devolver la IP del VPS, si no, espera más
# Luego ejecuta Certbot de nuevo
sudo certbot --apache -d tudominio.com -d www.tudominio.com
```

---

## 🔄 Si Cambias de VPS

1. Anota la nueva IP del VPS
2. Actualiza los registros A en el DNS
3. Espera propagación
4. Ejecuta de nuevo el script de despliegue en el nuevo VPS





