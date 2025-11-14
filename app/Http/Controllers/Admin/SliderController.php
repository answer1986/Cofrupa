<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('admin_authenticated')) {
                return redirect()->route('admin.login');
            }
            return $next($request);
        });
    }

    /**
     * Mostrar las imágenes del slider
     */
    public function index()
    {
        $images = Image::where('section', 'slider')
                      ->whereNotNull('slider_order')
                      ->orderBy('slider_order')
                      ->get();
        
        return view('admin.slider.index', compact('images'));
    }

    /**
     * Obtener datos de una imagen específica del slider
     */
    public function show($id)
    {
        \Log::info("=== INICIO show slider ===");
        \Log::info("ID solicitado: $id");
        
        try {
            $image = Image::findOrFail($id);
            \Log::info("Imagen encontrada: " . json_encode($image));
            
            return response()->json([
                'success' => true,
                'slide' => $image
            ]);
        } catch (\Exception $e) {
            \Log::error("Error en show slider: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'No se encontró la imagen'
            ], 404);
        }
    }

    /**
     * Agregar nueva imagen al slider
     */
    public function store(Request $request)
    {
        \Log::info("=== INICIO store slider ===");
        \Log::info("Datos recibidos: " . json_encode($request->all()));
        \Log::info("Tipo de imagen recibida: " . gettype($request->file('image')));
        \Log::info("Recibido como: " . ($request->hasFile('image') ? 'ARCHIVO' : 'NULL'));
        
        if (!$request->hasFile('image')) {
            \Log::error("ERROR: No se recibió archivo de imagen");
            return response()->json([
                'success' => false,
                'error' => 'Debe seleccionar una imagen para el slider'
            ], 400);
        }
        
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'title_es' => 'required|string',
            'title_en' => 'nullable|string',
            'content_es' => 'required|string',
            'content_en' => 'nullable|string',
            'alt_text_es' => 'required|string',
            'alt_text_en' => 'nullable|string'
        ]);

        // Obtener el siguiente orden disponible
        $nextOrder = Image::where('section', 'slider')
                         ->whereNotNull('slider_order')
                         ->max('slider_order') + 1;

        $image = $request->file('image');
        $fileName = time() . '_slider_' . $nextOrder . '.' . $image->getClientOriginalExtension();
        $path = 'image/uploads/' . $fileName;

        // Crear directorio si no existe
        $uploadDir = public_path('image/uploads');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Mover la imagen principal
        $image->move($uploadDir, $fileName);

        // Procesar thumbnail
        $thumbnailPath = $path; // Por defecto usar la misma imagen
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailFileName = time() . '_slider_thumb_' . $nextOrder . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnailPath = 'image/uploads/' . $thumbnailFileName;
            $thumbnail->move($uploadDir, $thumbnailFileName);
        }

        // Crear registro en base de datos
        Image::create([
            'key' => 'slider_image_' . $nextOrder,
            'path' => $path,
            'thumbnail_path' => $thumbnailPath,
            'alt_text_es' => $request->alt_text_es,
            'alt_text_en' => $request->alt_text_en ?: $request->alt_text_es,
            'title_es' => $request->title_es,
            'title_en' => $request->title_en ?: $request->title_es,
            'content_es' => $request->content_es,
            'content_en' => $request->content_en ?: $request->content_es,
            'section' => 'slider',
            'description' => 'Imagen del slider: ' . $request->title_es,
            'is_active' => true,
            'slider_order' => $nextOrder
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Imagen agregada al slider exitosamente',
            'reload' => true
        ]);
    }

    /**
     * Actualizar imagen del slider
     */
    public function update(Request $request, $id)
    {
        \Log::info("=== INICIO update slider ===");
        \Log::info("ID: $id");
        \Log::info("Datos recibidos: " . json_encode($request->all()));
        \Log::info("Archivos recibidos: " . json_encode($request->file()));
        
        $image = Image::findOrFail($id);
        \Log::info("Imagen encontrada: " . json_encode($image->toArray()));
        
        // Debug de campos específicos
        \Log::info("title_es: " . ($request->title_es ?? 'NULL'));
        \Log::info("content_es: " . ($request->content_es ?? 'NULL'));
        \Log::info("alt_text_es: " . ($request->alt_text_es ?? 'NULL'));
        
        // Si los campos requeridos están vacíos, usar los valores actuales de la imagen
        $title_es = $request->title_es ?: $image->title_es;
        $content_es = $request->content_es ?: $image->content_es;
        $alt_text_es = $request->alt_text_es ?: $image->alt_text_es;
        
        \Log::info("Valores procesados:");
        \Log::info("title_es procesado: " . $title_es);
        \Log::info("content_es procesado: " . $content_es);
        \Log::info("alt_text_es procesado: " . $alt_text_es);
        
        try {
            // Validar con los valores procesados
            $validatedData = $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
                'alt_text_es' => 'nullable|string',
                'alt_text_en' => 'nullable|string'
            ]);
            
            // Validar manualmente los campos requeridos
            if (empty($title_es)) {
                throw new \Exception("El título en español es requerido");
            }
            if (empty($content_es)) {
                throw new \Exception("El contenido en español es requerido");
            }
            if (empty($alt_text_es)) {
                throw new \Exception("El texto alternativo en español es requerido");
            }
            
            \Log::info("Validación pasada exitosamente");
        } catch (\Exception $e) {
            \Log::error("Error en validación: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error de validación: ' . $e->getMessage()
            ], 422);
        }

        $updateData = [
            'title_es' => $title_es,
            'title_en' => $request->title_en ?: $title_es,
            'content_es' => $content_es,
            'content_en' => $request->content_en ?: $content_es,
            'alt_text_es' => $alt_text_es,
            'alt_text_en' => $request->alt_text_en ?: $alt_text_es,
        ];
        
        \Log::info("UpdateData preparado: " . json_encode($updateData));

        $uploadDir = public_path('image/uploads');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Actualizar imagen principal si se proporciona una nueva
        if ($request->hasFile('image')) {
            \Log::info("=== REEMPLAZANDO IMAGEN PRINCIPAL ===");
            \Log::info("Imagen anterior: " . $image->path);
            
            // Eliminar imagen anterior si está en uploads
            if ($image->path && file_exists(public_path($image->path)) && strpos($image->path, 'uploads/') !== false) {
                \Log::info("Eliminando archivo anterior: " . public_path($image->path));
                unlink(public_path($image->path));
                \Log::info("Archivo anterior eliminado");
            }

            $imageFile = $request->file('image');
            $fileName = time() . '_slider_' . $image->slider_order . '.' . $imageFile->getClientOriginalExtension();
            $path = 'image/uploads/' . $fileName;
            
            \Log::info("Nuevo archivo: " . $fileName);
            \Log::info("Tamaño del archivo: " . $imageFile->getSize() . " bytes");
            \Log::info("Moviendo archivo a: " . $uploadDir . '/' . $fileName);
            
            $moveResult = $imageFile->move($uploadDir, $fileName);
            \Log::info("Archivo movido: " . ($moveResult ? 'SÍ' : 'NO'));
            
            if (file_exists($uploadDir . '/' . $fileName)) {
                \Log::info("Verificación: Archivo existe en destino");
                $updateData['path'] = $path;
                \Log::info("Nueva ruta guardada: " . $path);
            } else {
                \Log::error("ERROR: Archivo no existe en destino después del move");
            }
        } else {
            \Log::info("No se proporcionó nueva imagen principal");
        }

        // Actualizar thumbnail si se proporciona uno nuevo
        if ($request->hasFile('thumbnail')) {
            // Eliminar thumbnail anterior si está en uploads y es diferente de la imagen principal
            if ($image->thumbnail_path && $image->thumbnail_path !== $image->path && 
                file_exists(public_path($image->thumbnail_path)) && strpos($image->thumbnail_path, 'uploads/') !== false) {
                unlink(public_path($image->thumbnail_path));
            }

            $thumbnail = $request->file('thumbnail');
            $thumbnailFileName = time() . '_slider_thumb_' . $image->slider_order . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnailPath = 'image/uploads/' . $thumbnailFileName;
            $thumbnail->move($uploadDir, $thumbnailFileName);
            $updateData['thumbnail_path'] = $thumbnailPath;
        }

        $image->update($updateData);
        
        \Log::info("Imagen actualizada exitosamente");
        \Log::info("Datos finales: " . json_encode($image->fresh()));

        return response()->json([
            'success' => true,
            'message' => 'Imagen del slider actualizada exitosamente'
        ]);
    }

    /**
     * Eliminar imagen del slider
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $image = Image::findOrFail($request->id);
        
        // Eliminar archivos físicos si están en uploads
        if ($image->path && file_exists(public_path($image->path)) && strpos($image->path, 'uploads/') !== false) {
            unlink(public_path($image->path));
        }
        
        if ($image->thumbnail_path && $image->thumbnail_path !== $image->path && 
            file_exists(public_path($image->thumbnail_path)) && strpos($image->thumbnail_path, 'uploads/') !== false) {
            unlink(public_path($image->thumbnail_path));
        }

        $image->delete();

        // Reordenar las imágenes restantes
        $this->reorderImages();

        return response()->json([
            'success' => true,
            'message' => 'Imagen eliminada del slider',
            'reload' => true
        ]);
    }

    /**
     * Reordenar imágenes del slider
     */
    public function reorder(Request $request)
    {
        \Log::info("=== INICIO reorder slider ===");
        \Log::info("Datos recibidos: " . json_encode($request->all()));
        
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer'
        ]);

        try {
            $images = Image::where('section', 'slider')
                          ->whereNotNull('slider_order')
                          ->get();

            \Log::info("Imágenes encontradas: " . $images->count());

            foreach ($request->order as $index => $imageId) {
                $image = $images->find($imageId);
                if ($image) {
                    $oldOrder = $image->slider_order;
                    $newOrder = $index + 1;
                    $image->slider_order = $newOrder;
                    $image->save();
                    \Log::info("Imagen ID {$imageId}: orden {$oldOrder} -> {$newOrder}");
                } else {
                    \Log::warning("No se encontró imagen con ID: {$imageId}");
                }
            }

            \Log::info("Reordenamiento completado exitosamente");

            return response()->json([
                'success' => true,
                'message' => 'Orden actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            \Log::error("Error en reorder: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al actualizar el orden'
            ], 500);
        }
    }

    /**
     * Actualizar orden de una imagen específica del slider
     */
    public function updateSliderOrder(Request $request)
    {
        \Log::info("=== INICIO updateSliderOrder ===");
        \Log::info("Datos recibidos: " . json_encode($request->all()));
        
        $request->validate([
            'key' => 'required|string',
            'new_order' => 'required|integer|min:1|max:10'
        ]);

        try {
            // Buscar la imagen por clave
            $image = Image::where('key', $request->key)
                         ->where('section', 'slider')
                         ->first();

            if (!$image) {
                \Log::error("No se encontró imagen con key: " . $request->key);
                return response()->json([
                    'success' => false,
                    'error' => 'Imagen no encontrada'
                ], 404);
            }

            $oldOrder = $image->slider_order;
            $newOrder = $request->new_order;

            \Log::info("Cambiando orden de imagen ID {$image->id} de {$oldOrder} a {$newOrder}");

            // Si el nuevo orden es diferente, reorganizar
            if ($oldOrder != $newOrder) {
                // Obtener todas las imágenes del slider ordenadas
                $allImages = Image::where('section', 'slider')
                                 ->whereNotNull('slider_order')
                                 ->orderBy('slider_order')
                                 ->get();

                // Remover la imagen actual de su posición
                $allImages = $allImages->reject(function($img) use ($image) {
                    return $img->id === $image->id;
                });

                // Insertar la imagen en la nueva posición
                $allImages->splice($newOrder - 1, 0, [$image]);

                // Actualizar todos los órdenes
                foreach ($allImages as $index => $img) {
                    $img->slider_order = $index + 1;
                    $img->save();
                    \Log::info("Imagen ID {$img->id} actualizada a orden " . ($index + 1));
                }
            }

            \Log::info("Orden actualizado exitosamente");

            return response()->json([
                'success' => true,
                'message' => 'Orden del slider actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            \Log::error("Error en updateSliderOrder: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al actualizar el orden del slider'
            ], 500);
        }
    }

    /**
     * Reordenar automáticamente las imágenes
     */
    private function reorderImages()
    {
        $images = Image::where('section', 'slider')
                      ->whereNotNull('slider_order')
                      ->orderBy('slider_order')
                      ->get();

        foreach ($images as $index => $image) {
            $image->slider_order = $index + 1;
            $image->save();
        }
    }
} 