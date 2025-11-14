# 🍇 Cofrupa - Exportadora de Ciruelas D'Agen

Sitio web corporativo de Cofrupa, empresa chilena líder en exportación de ciruelas D'Agen y productos derivados.

## 🌟 Características

- ✅ **Multi-idioma**: Español, Inglés y Chino
- ✅ **Panel de Administración**: Edición en línea de contenido e imágenes
- ✅ **Responsive Design**: Optimizado para todos los dispositivos
- ✅ **Mapa Interactivo**: Visualización de mercados con D3.js
- ✅ **Formulario de Contacto**: Con Google reCAPTCHA
- ✅ **Videos Background**: Hero section y sección Quiénes Somos
- ✅ **Certificaciones**: Display de BRC-FDA
- ✅ **Reloj Chile**: Hora local en tiempo real

## 🚀 Despliegue en VPS con Apache

### Opción 1: Script Automático (Recomendado)
```bash
sudo bash deploy-vps.sh
```

### Opción 2: Manual
Ver guía completa: **[DESPLIEGUE_VPS_APACHE.md](DESPLIEGUE_VPS_APACHE.md)**

### Guía Rápida
Ver: **[DESPLIEGUE_RAPIDO.md](DESPLIEGUE_RAPIDO.md)**

## 📋 Documentación Adicional

- 📄 [Configurar DNS](CONFIGURAR_DNS.md)
- 📄 [Configurar reCAPTCHA](CONFIGURAR_RECAPTCHA.md)
- 📄 [Usar Cloudflare Tunnel](USAR_CLOUDFLARE_TUNNEL.md)
- 📄 [Instalación Carrusel](INSTALACION_CARRUSEL.md)

## 🔐 Panel de Administración

**URL:** `/admin/login`

**Credenciales por defecto:**
- Email: `admin@cofrupa.com`
- Contraseña: `Cofrupa2024!`

⚠️ **Cambiar después del primer login**

## 🛠️ Desarrollo Local

```bash
# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# Luego:
php artisan migrate

# Iniciar servidor
php artisan serve
```

## 🎨 Diseño

Figma: https://www.figma.com/team_invite/redeem/VeA1yNCLlijWeSyPZOWcRs

## 🏢 Stack Tecnológico

- **Backend:** Laravel 9.x
- **Frontend:** Blade Templates, CSS3, JavaScript
- **Base de Datos:** MySQL
- **Servidor Web:** Apache 2.4
- **PHP:** 8.2+
- **Librerías:** D3.js, TopoJSON, Bootstrap 5

## 📞 Soporte

Desarrollo: [R3Q](https://r3q.cl)
