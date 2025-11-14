# Cómo Configurar Google reCAPTCHA para el Formulario de Contacto

## Paso 1: Obtener las claves de reCAPTCHA

1. Ve a la consola de Google reCAPTCHA: https://www.google.com/recaptcha/admin/create

2. Inicia sesión con tu cuenta de Google

3. Completa el formulario:
   - **Etiqueta**: Cofrupa Website
   - **Tipo de reCAPTCHA**: Selecciona "reCAPTCHA v2" → "Casilla de verificación 'No soy un robot'"
   - **Dominios**: Agrega tu dominio (ejemplo: cofrupa.cl)
   - Acepta los términos de servicio
   - Haz clic en "Enviar"

4. Google te proporcionará dos claves:
   - **Clave del sitio (Site Key)**: Esta es pública y va en el código HTML
   - **Clave secreta (Secret Key)**: Esta es privada (no la compartas)

## Paso 2: Actualizar el Código

### En el archivo: `resources/views/index.blade.php`

Busca la línea:
```html
<div class="g-recaptcha" data-sitekey="6LfYourSiteKeyHere"></div>
```

Reemplaza `6LfYourSiteKeyHere` con tu **Clave del sitio (Site Key)**:
```html
<div class="g-recaptcha" data-sitekey="TU_CLAVE_DEL_SITIO_AQUI"></div>
```

## Paso 3: Configurar la Validación del Servidor (Formspree)

Como estás usando Formspree, el reCAPTCHA se validará automáticamente en el navegador. 

Si quieres validación adicional en el servidor, necesitarás:

1. Ir a tu cuenta de Formspree: https://formspree.io/
2. Seleccionar tu formulario (xnnaekdr)
3. En Settings → Spam Protection
4. Agregar tu **Clave secreta (Secret Key)** de reCAPTCHA

## Ejemplo de claves (NO USAR ESTAS, son de ejemplo):

```
Site Key: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
Secret Key: 6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
```

## Verificar que Funciona

1. Abre tu sitio web
2. Ve a la sección de contacto
3. Deberías ver la casilla de verificación "No soy un robot"
4. Intenta enviar el formulario sin marcar la casilla → debe aparecer una alerta
5. Marca la casilla y envía el formulario → debe funcionar normalmente

## Notas Importantes

- ✅ El reCAPTCHA funciona en **localhost** para pruebas
- ✅ Los placeholders del formulario cambian automáticamente según el idioma (ES/EN/ZH)
- ✅ El captcha se adapta al idioma del navegador del usuario
- ⚠️ Si cambias de dominio, debes agregar el nuevo dominio en la consola de reCAPTCHA

## Soporte

Si tienes problemas:
- Verifica que las claves sean correctas
- Asegúrate de que el dominio esté registrado en Google reCAPTCHA
- Revisa la consola del navegador (F12) para errores de JavaScript

