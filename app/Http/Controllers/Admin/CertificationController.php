<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificationController extends Controller
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
     * Mostrar las imágenes de certificaciones
     */
    public function index()
    {
        // Obtener todas las certificaciones ordenadas
        $certifications = Image::where('section', 'certificaciones')
                              ->whereNotNull('cert_order')
                              ->orderBy('cert_order')
                              ->get();
        
        // Obtener certificaciones del footer
        $footerCertifications = Image::where('section', 'footer')
                                    ->where('key', 'LIKE', 'footer_cert_%')
                                    ->orderBy('key')
                                    ->get();
        
        return view('admin.certifications.index', compact('certifications', 'footerCertifications'));
    }

    /**
     * Agregar nueva certificación
     */
    public function store(Request $request)
    {
        \Log::info("=== INICIO store certification ===");
        \Log::info("Datos recibidos: " . json_encode($request->all()));
        
        if (!$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'error' => 'Debe seleccionar una imagen para la certificación'
            ], 400);
        }
        
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'title_es' => 'required|string',
            'title_en' => 'nullable|string',
            'alt_text_es' => 'required|string',
            'alt_text_en' => 'nullable|string'
        ]);

        // Obtener el siguiente orden disponible
        $nextOrder = Image::where('section', 'certificaciones')
                         ->whereNotNull('cert_order')
                         ->max('cert_order');
        $nextOrder = ($nextOrder ?? 0) + 1;

        // Generar una clave única
        $keyCounter = 1;
        do {
            $uniqueKey = 'cert_' . $keyCounter;
            $existingKey = Image::where('key', $uniqueKey)->first();
            $keyCounter++;
        } while ($existingKey);

        $image = $request->file('image');
        $fileName = time() . '_' . $uniqueKey . '.' . $image->getClientOriginalExtension();
        $path = 'image/uploads/' . $fileName;

        // Crear directorio si no existe
        $uploadDir = public_path('image/uploads');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Mover la imagen
        $image->move($uploadDir, $fileName);

        // Crear registro en base de datos
        Image::create([
            'key' => $uniqueKey,
            'path' => $path,
            'alt_text_es' => $request->alt_text_es,
            'alt_text_en' => $request->alt_text_en ?: $request->alt_text_es,
            'title_es' => $request->title_es,
            'title_en' => $request->title_en ?: $request->title_es,
            'section' => 'certificaciones',
            'description' => 'Certificación: ' . $request->title_es,
            'is_active' => true,
            'cert_order' => $nextOrder
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificación agregada exitosamente',
            'reload' => true
        ]);
    }

    /**
     * Actualizar imagen de certificación
     */
    public function update(Request $request, $id)
    {
        \Log::info("=== INICIO update certification ===");
        \Log::info("ID: $id");
        
        $image = Image::findOrFail($id);
        
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'alt_text_es' => 'required|string',
            'alt_text_en' => 'nullable|string',
            'title_es' => 'required|string',
            'title_en' => 'nullable|string'
        ]);

        $updateData = [
            'title_es' => $request->title_es,
            'title_en' => $request->title_en ?: $request->title_es,
            'alt_text_es' => $request->alt_text_es,
            'alt_text_en' => $request->alt_text_en ?: $request->alt_text_es,
        ];

        $uploadDir = public_path('image/uploads');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Actualizar imagen si se proporciona una nueva
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si está en uploads
            if ($image->path && file_exists(public_path($image->path)) && strpos($image->path, 'uploads/') !== false) {
                unlink(public_path($image->path));
            }

            $imageFile = $request->file('image');
            $fileName = time() . '_' . $image->key . '.' . $imageFile->getClientOriginalExtension();
            $path = 'image/uploads/' . $fileName;
            
            $imageFile->move($uploadDir, $fileName);
            $updateData['path'] = $path;
        }

        $image->update($updateData);
        
        \Log::info("Certificación actualizada exitosamente");

        return response()->json([
            'success' => true,
            'message' => 'Certificación actualizada exitosamente'
        ]);
    }

    /**
     * Eliminar certificación
     */
    public function destroy(Request $request)
    {
        \Log::info("=== INICIO destroy certification ===");
        
        $request->validate([
            'id' => 'required|integer'
        ]);

        $image = Image::findOrFail($request->id);
        
        // Eliminar archivo físico si está en uploads
        if ($image->path && file_exists(public_path($image->path)) && strpos($image->path, 'uploads/') !== false) {
            unlink(public_path($image->path));
        }

        $image->delete();

        // Reordenar las certificaciones restantes
        $this->reorderCertifications();

        \Log::info("Certificación eliminada exitosamente");

        return response()->json([
            'success' => true,
            'message' => 'Certificación eliminada exitosamente',
            'reload' => true
        ]);
    }

    /**
     * Reordenar certificaciones
     */
    public function reorder(Request $request)
    {
        \Log::info("=== INICIO reorder certifications ===");
        \Log::info("Datos recibidos: " . json_encode($request->all()));
        
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer'
        ]);

        try {
            $certifications = Image::where('section', 'certificaciones')
                                  ->whereNotNull('cert_order')
                                  ->get();

            foreach ($request->order as $index => $certId) {
                $cert = $certifications->find($certId);
                if ($cert) {
                    $cert->cert_order = $index + 1;
                    $cert->save();
                    \Log::info("Certificación ID {$certId}: orden actualizado a " . ($index + 1));
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
     * Reordenar automáticamente las certificaciones
     */
    private function reorderCertifications()
    {
        $certifications = Image::where('section', 'certificaciones')
                              ->whereNotNull('cert_order')
                              ->orderBy('cert_order')
                              ->get();

        foreach ($certifications as $index => $cert) {
            $cert->cert_order = $index + 1;
            $cert->save();
        }
    }
}
