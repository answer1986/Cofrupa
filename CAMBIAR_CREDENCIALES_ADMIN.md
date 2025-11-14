# 🔐 Cambiar Credenciales de Administrador

## 📍 Credenciales Actuales

**Email:** `admin@cofrupa.com`  
**Contraseña:** `Cofrupa2024!`

⚠️ **Es importante cambiar estas credenciales por seguridad**

---

## 🛠️ Cómo Cambiar las Credenciales

### Opción 1: Editar el Código (Recomendado para cambio inicial)

**Archivo:** `app/Http/Controllers/Admin/AdminController.php`

```bash
# En el VPS o en local
nano app/Http/Controllers/Admin/AdminController.php
```

**Busca la línea 42 (aproximadamente) y cambia:**

```php
// Credenciales del administrador de Cofrupa
if ($request->email === 'admin@cofrupa.com' && $request->password === 'Cofrupa2024!') {
    session(['admin_authenticated' => true]);
    return redirect()->route('admin.dashboard');
}
```

**Por tus nuevas credenciales:**

```php
// Credenciales del administrador de Cofrupa
if ($request->email === 'tu-nuevo-email@cofrupa.com' && $request->password === 'TuNuevaContraseñaSegura123!') {
    session(['admin_authenticated' => true]);
    return redirect()->route('admin.dashboard');
}
```

**Guardar y aplicar cambios:**

```bash
# Si estás en el VPS
cd /var/www/cofrupa
php artisan config:clear
php artisan cache:clear
systemctl restart php8.2-fpm
```

---

## 🔒 Opción 2: Sistema de Múltiples Usuarios (Avanzado)

Para implementar un sistema completo con múltiples administradores y hash de contraseñas:

### 1. Crear Migración para Tabla de Admins

```bash
php artisan make:migration create_admins_table
```

**Editar la migración:**

```php
public function up()
{
    Schema::create('admins', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
```

```bash
php artisan migrate
```

### 2. Crear Modelo Admin

```bash
php artisan make:model Admin
```

**Contenido de `app/Models/Admin.php`:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $fillable = ['name', 'email', 'password', 'is_active'];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
```

### 3. Modificar AdminController

**En `app/Http/Controllers/Admin/AdminController.php`:**

```php
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

public function authenticate(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Buscar admin en base de datos
    $admin = Admin::where('email', $request->email)
                  ->where('is_active', true)
                  ->first();

    if ($admin && Hash::check($request->password, $admin->password)) {
        session([
            'admin_authenticated' => true,
            'admin_id' => $admin->id,
            'admin_name' => $admin->name
        ]);
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas']);
}
```

### 4. Crear Primer Admin (usar tinker)

```bash
php artisan tinker
```

**Dentro de tinker:**

```php
Admin::create([
    'name' => 'Administrador Cofrupa',
    'email' => 'admin@cofrupa.com',
    'password' => 'TuContraseñaSegura123!',
    'is_active' => true
]);

// Verificar
Admin::all();

// Salir
exit
```

### 5. Crear Interfaz para Gestionar Admins (Opcional)

Puedes crear un CRUD en el panel admin para agregar/editar/eliminar administradores.

---

## 🎯 Recomendaciones de Seguridad

### ✅ Buenas Contraseñas:
- Mínimo 12 caracteres
- Mayúsculas y minúsculas
- Números
- Símbolos especiales
- No usar información personal

### ❌ Malas Contraseñas:
- admin123
- password
- 12345678
- cofrupa2024

### 📝 Ejemplos de Buenas Contraseñas:
- `Cfr#2024!pRune$`
- `Ag3n*Prun3s#2024`
- `C0frup@Exp0rt!`

---

## 🔄 Aplicar Cambios en el VPS

Después de cambiar las credenciales en el código:

```bash
# Conectar al VPS
ssh root@tu-ip-del-vps

# Ir al directorio del proyecto
cd /var/www/cofrupa

# Si subiste el archivo modificado
# (usando scp desde tu Mac)
scp app/Http/Controllers/Admin/AdminController.php root@tu-ip:/var/www/cofrupa/app/Http/Controllers/Admin/

# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Reiniciar PHP
systemctl restart php8.2-fpm

# Probar login
echo "✅ Cambios aplicados. Intenta hacer login con las nuevas credenciales"
```

---

## 🧪 Verificar que Funciona

1. Cierra todas las sesiones actuales
2. Ve a `https://tudominio.com/admin/logout`
3. Ve a `https://tudominio.com/admin/login`
4. Intenta con las **nuevas** credenciales
5. Debería funcionar

---

## 💡 Tips

- **Anota las credenciales** en un lugar seguro (gestor de contraseñas)
- **No compartas** las credenciales por email/chat sin encriptar
- **Cambia la contraseña** cada 3-6 meses
- Si implementas el sistema de BD, puedes tener múltiples admins
- Considera implementar **autenticación de 2 factores** (2FA) en el futuro

---

## 🆘 Recuperar Acceso si Olvidas la Contraseña

### Si usas el sistema hardcodeado (actual):

```bash
# Edita el archivo y pon una contraseña temporal
ssh root@tu-ip-del-vps
cd /var/www/cofrupa
nano app/Http/Controllers/Admin/AdminController.php

# Cambia la línea 42 con una contraseña temporal
# Luego limpia caché y prueba
```

### Si usas el sistema de BD:

```bash
php artisan tinker

# Cambiar contraseña
$admin = Admin::where('email', 'admin@cofrupa.com')->first();
$admin->password = 'NuevaContraseña123!';
$admin->save();

exit
```

---

## 📞 Soporte

Para cambios avanzados o implementar el sistema de múltiples usuarios, contacta al equipo de desarrollo.





