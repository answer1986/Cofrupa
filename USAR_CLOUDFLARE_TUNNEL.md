# 🌐 Cómo Mostrar la Web al Cliente con Cloudflare Tunnel

## Problema Actual
El CSS no se carga correctamente porque Laravel está generando URLs con `localhost:8000` en lugar de la URL de Cloudflare Tunnel.

## ✅ Solución - Pasos a Seguir

### 1️⃣ Iniciar tu servidor Laravel
```bash
cd /Users/alvaroriquelmevilla/Desktop/Cofrupa
php artisan serve
```
Esto iniciará el servidor en `http://localhost:8000`

### 2️⃣ Abrir NUEVA terminal y crear el tunnel
```bash
cloudflared tunnel --url http://localhost:8000 --loglevel info
```

Espera a que aparezca la URL del tunnel, algo como:
```
https://deaf-webshots-joel-penalty.trycloudflare.com
```

**⚠️ IMPORTANTE:** Copia esta URL completa.

### 3️⃣ Configurar la URL en Laravel

Opción A - Usando el script automatizado (Recomendado):
```bash
./setup-cloudflare-tunnel.sh https://TU-URL-AQUI.trycloudflare.com
```

Opción B - Manual:
```bash
# Abrir el archivo .env
nano .env

# Buscar la línea APP_URL y cambiarla a:
APP_URL=https://TU-URL-AQUI.trycloudflare.com

# Guardar (Ctrl+O, Enter, Ctrl+X)

# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
```

### 4️⃣ Verificar que funciona

Abre la URL del tunnel en tu navegador:
```
https://TU-URL-AQUI.trycloudflare.com
```

✅ El CSS debería cargarse correctamente
✅ Las imágenes deberían verse
✅ Los videos deberían reproducirse

## 🔄 Cada vez que inicies Cloudflare Tunnel

**IMPORTANTE:** Cloudflare Tunnel genera una URL diferente cada vez. Debes:

1. Obtener la nueva URL del tunnel
2. Ejecutar: `./setup-cloudflare-tunnel.sh https://NUEVA-URL.trycloudflare.com`
3. Recargar la página en el navegador

## 📝 Comando Rápido Todo-en-Uno

Puedes usar este comando para configurarlo rápidamente:

```bash
# Reemplaza YOUR_TUNNEL_URL con tu URL de Cloudflare
export TUNNEL_URL="https://deaf-webshots-joel-penalty.trycloudflare.com"
./setup-cloudflare-tunnel.sh $TUNNEL_URL
```

## 🛠️ Solución de Problemas

### Problema: El CSS todavía no carga
**Solución:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```
Luego recarga la página con Ctrl+F5 (forzar recarga sin caché)

### Problema: Las imágenes no se ven
**Verificar que estén en `/public/image/`:**
```bash
ls -la public/image/
```

### Problema: El tunnel se desconecta
**Solución:** Es normal, Cloudflare Tunnel gratuito es temporal. Solo reinicia el tunnel y actualiza la URL.

## 💡 Consejo para Producción

Para un dominio permanente (producción), deberías:

1. Configurar un dominio real (cofrupa.cl)
2. Usar Cloudflare Tunnel con cuenta (no anónimo)
3. Configurar `APP_URL=https://cofrupa.cl` en el `.env`

## 📧 Compartir con el Cliente

Una vez configurado correctamente:
1. Envía la URL del tunnel: `https://xxx.trycloudflare.com`
2. El cliente podrá ver la web completa con CSS, animaciones, videos, etc.
3. La sesión durará mientras mantengas los 2 terminales abiertas

## ⚠️ Recordatorios

- ✅ Mantén ambas terminales abiertas (servidor + tunnel)
- ✅ Actualiza APP_URL cada vez que cambies de URL
- ✅ Limpia cachés después de cambiar configuración
- ✅ El tunnel gratuito es temporal (para demos)

