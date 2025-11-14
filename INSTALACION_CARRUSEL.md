# Instalación del Sistema de Carrusel Dinámico

## 1. Ejecutar el ALTER en la base de datos

```sql
-- Agregar columna para el orden del carrusel
ALTER TABLE images ADD COLUMN carousel_order INT NULL COMMENT 'Orden en el carrusel (NULL para no-carrusel)';

-- Insertar las imágenes del carrusel existentes
INSERT INTO images (`key`, `path`, `alt_text_es`, `alt_text_en`, `section`, `description`, `is_active`, `carousel_order`)
SELECT 'carousel_image_1', './image/frutas secas.jpeg', 'Frutas Secas', 'Dried Fruits', 'nosotros', 'Carrusel - Frutas secas', 1, 1
WHERE NOT EXISTS (SELECT 1 FROM images WHERE `key` = 'carousel_image_1');

INSERT INTO images (`key`, `path`, `alt_text_es`, `alt_text_en`, `section`, `description`, `is_active`, `carousel_order`)
SELECT 'carousel_image_2', './image/pasas.jpeg', 'Pasas', 'Raisins', 'nosotros', 'Carrusel - Pasas', 1, 2
WHERE NOT EXISTS (SELECT 1 FROM images WHERE `key` = 'carousel_image_2');

INSERT INTO images (`key`, `path`, `alt_text_es`, `alt_text_en`, `section`, `description`, `is_active`, `carousel_order`)
SELECT 'carousel_image_3', './image/ciruela.png', 'Ciruelas', 'Plums', 'nosotros', 'Carrusel - Ciruelas', 1, 3
WHERE NOT EXISTS (SELECT 1 FROM images WHERE `key` = 'carousel_image_3');

INSERT INTO images (`key`, `path`, `alt_text_es`, `alt_text_en`, `section`, `description`, `is_active`, `carousel_order`)
SELECT 'carousel_image_4', './image/Jefe.jpeg', 'Jefe', 'Boss', 'nosotros', 'Carrusel - Jefe', 1, 4
WHERE NOT EXISTS (SELECT 1 FROM images WHERE `key` = 'carousel_image_4');
```

## 2. Funcionalidades implementadas

### ✅ Carrusel Dinámico
- **Antes**: 4 imágenes fijas hardcodeadas
- **Ahora**: Número ilimitado de imágenes desde base de datos
- **Orden**: Configurable via campo `carousel_order`

### ✅ Panel de Administración
- **Ruta**: `/admin/carousel`
- **Funciones**:
  - Agregar nuevas imágenes
  - Eliminar imágenes
  - Reordenar imágenes
  - Vista previa en tiempo real

### ✅ Edición Visual
- **Botón "Gestionar Carrusel"** aparece en modo edición
- **Edición individual** de imágenes con lápiz
- **Integración** con el sistema de edición existente

## 3. Partes del código modificadas

### 📁 `app/Http/Controllers/Admin/CarouselController.php`
- **Nuevo controlador** para gestionar el carrusel
- **Funciones**: `index()`, `store()`, `destroy()`, `reorder()`

### 📁 `app/Models/Image.php`
- **Campo agregado**: `carousel_order` al $fillable

### 📁 `app/helpers.php`
- **Nueva función**: `getCarouselImages()` para obtener imágenes dinámicamente

### 📁 `resources/views/index.blade.php`
- **Carrusel dinámico**: Reemplazado código estático por dinámico
- **JavaScript mejorado**: Manejo de número variable de imágenes

### 📁 `resources/views/admin/carousel/index.blade.php`
- **Nueva vista** para gestionar el carrusel
- **Interfaz drag-and-drop** para reordenar
- **Modal** para agregar nuevas imágenes

### 📁 `routes/web.php`
- **Nuevas rutas** para el carrusel:
  - `GET /admin/carousel`
  - `POST /admin/carousel/store`
  - `POST /admin/carousel/destroy`
  - `POST /admin/carousel/reorder`

## 4. Cómo usar el sistema

### Para agregar imágenes:
1. Entrar al admin: `http://127.0.0.1:8000/admin/login`
2. Ir a "Carrusel" en el menú lateral
3. Hacer clic en "Agregar Imagen"
4. Subir imagen y llenar datos
5. Las imágenes aparecen automáticamente en el carrusel

### Para reordenar:
1. Usar los botones ⬆️ ⬇️ en el panel de carrusel
2. El orden se refleja instantáneamente en el sitio

### Para eliminar:
1. Usar el botón 🗑️ en el panel de carrusel
2. Las imágenes restantes se reordenan automáticamente

## 5. Ventajas del nuevo sistema

- **Escalable**: Ilimitadas imágenes vs 4 fijas
- **Fácil de usar**: Interface visual intuitiva
- **Automático**: JavaScript se adapta al número de imágenes
- **Consistente**: Integrado con el sistema de edición existente
- **Performante**: Optimizado para cargar solo las imágenes necesarias

## 6. Tecnologías utilizadas

- **Backend**: Laravel (PHP)
- **Frontend**: Bootstrap 5 + JavaScript vanilla
- **Database**: MySQL (campo `carousel_order`)
- **Files**: Sistema de uploads existente
- **Integration**: Sistema de edición visual existente 