<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('admin_authenticated')) {
                return redirect()->route('admin.login');
            }
            return $next($request);
        })->except(['login', 'authenticate']);
    }

    public function login()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Credenciales del administrador de Cofrupa
        if ($request->email === 'admin@cofrupa.com' && $request->password === 'Cofrupa2024!') {
            session(['admin_authenticated' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function dashboard()
    {
        $totalContents = Content::count();
        $totalImages = Image::count();
        $totalContacts = Content::where('section', 'contacto')->count();
        $totalFooter = Content::where('section', 'footer')->count() + Image::where('section', 'footer')->count();
        
        return view('admin.dashboard', compact('totalContents', 'totalImages', 'totalContacts', 'totalFooter'));
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('admin.login');
    }

    // NUEVAS FUNCIONES PARA EDICIÓN INLINE
    public function updateContent(Request $request)
    {
        if (!session('admin_authenticated')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'key' => 'required|string',
            'value_es' => 'nullable|string',
            'value_en' => 'nullable|string',
            'section' => 'nullable|string'
        ]);

        $content = Content::firstOrCreate(
            ['key' => $request->key],
            [
                'section' => $request->section ?? 'general',
                'type' => 'text'
            ]
        );

        $content->update([
            'value_es' => $request->value_es,
            'value_en' => $request->value_en
        ]);

        return response()->json(['success' => true, 'message' => 'Contenido actualizado']);
    }

    public function updateImage(Request $request)
    {
        \Log::info('=== INICIO updateImage ===');
        \Log::info('Request data: ', $request->all());
        \Log::info('Files: ', $request->allFiles());

        if (!session('admin_authenticated')) {
            \Log::error('Usuario no autenticado');
            return response()->json(['error' => 'No autorizado'], 403);
        }

        try {
            \Log::info('Validando request...');
            $request->validate([
                'key' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:10240',
                'alt_text_es' => 'nullable|string',
                'alt_text_en' => 'nullable|string'
            ]);
            \Log::info('Validación exitosa');

            $image = $request->file('image');
            \Log::info('Imagen recibida: ', [
                'name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'mime' => $image->getMimeType(),
                'extension' => $image->getClientOriginalExtension()
            ]);

            $fileName = time() . '_' . $request->key . '.' . $image->getClientOriginalExtension();
            $path = 'image/uploads/' . $fileName;
            \Log::info('Archivo destino: ' . $fileName);
            \Log::info('Path completo: ' . $path);
            
            // Crear directorio si no existe
            $uploadDir = public_path('image/uploads');
            \Log::info('Directorio uploads: ' . $uploadDir);
            
            if (!file_exists($uploadDir)) {
                \Log::info('Creando directorio uploads...');
                $created = mkdir($uploadDir, 0777, true);
                \Log::info('Directorio creado: ' . ($created ? 'SÍ' : 'NO'));
            } else {
                \Log::info('Directorio ya existe');
            }

            \Log::info('Intentando mover archivo...');
            $moved = $image->move($uploadDir, $fileName);
            \Log::info('Archivo movido: ' . ($moved ? 'SÍ' : 'NO'));
            \Log::info('Archivo final existe: ' . (file_exists($uploadDir . '/' . $fileName) ? 'SÍ' : 'NO'));

            \Log::info('Buscando registro en BD...');
            $imageRecord = Image::where('key', $request->key)->first();
            
            if ($imageRecord) {
                \Log::info('Registro existente encontrado: ', $imageRecord->toArray());
                
                // Eliminar imagen anterior si existe y no es la imagen por defecto
                if ($imageRecord->path && file_exists(public_path($imageRecord->path)) && strpos($imageRecord->path, 'uploads/') !== false) {
                    \Log::info('Eliminando imagen anterior: ' . $imageRecord->path);
                    $deleted = unlink(public_path($imageRecord->path));
                    \Log::info('Imagen anterior eliminada: ' . ($deleted ? 'SÍ' : 'NO'));
                }
                
                \Log::info('Actualizando registro existente...');
                $imageRecord->update([
                    'path' => $path,
                    'alt_text_es' => $request->alt_text_es,
                    'alt_text_en' => $request->alt_text_en,
                    'is_active' => true
                ]);
            } else {
                \Log::info('Creando nuevo registro...');
                $imageRecord = Image::create([
                    'key' => $request->key,
                    'section' => $request->section ?? 'general',
                    'path' => $path,
                    'alt_text_es' => $request->alt_text_es,
                    'alt_text_en' => $request->alt_text_en,
                    'is_active' => true
                ]);
            }
            
            \Log::info('Registro final: ', $imageRecord->fresh()->toArray());

            $response = [
                'success' => true, 
                'message' => 'Imagen actualizada',
                'new_path' => asset($path)
            ];
            \Log::info('Respuesta exitosa: ', $response);
            \Log::info('=== FIN updateImage EXITOSO ===');

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('ERROR en updateImage: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::info('=== FIN updateImage CON ERROR ===');
            
            return response()->json([
                'error' => 'Error al procesar imagen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage(Request $request)
    {
        \Log::info('=== INICIO deleteImage ===');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request all: ', $request->all());
        \Log::info('Request input: ', $request->input());
        \Log::info('Headers: ', $request->headers->all());

        if (!session('admin_authenticated')) {
            \Log::error('Usuario no autenticado en deleteImage');
            return response()->json(['error' => 'No autorizado'], 403);
        }

        try {
            \Log::info('Validando request en deleteImage...');
            $request->validate([
                'key' => 'required|string'
            ]);
            \Log::info('Validación exitosa en deleteImage');

            $key = $request->input('key');
            \Log::info('Buscando imagen para eliminar con key: ' . $key);
            
            $imageRecord = Image::where('key', $key)->first();
            
            if ($imageRecord) {
                \Log::info('Imagen encontrada para eliminar: ', $imageRecord->toArray());
                
                // Eliminar archivo físico si existe y está en uploads
                if ($imageRecord->path && file_exists(public_path($imageRecord->path)) && strpos($imageRecord->path, 'uploads/') !== false) {
                    \Log::info('Eliminando archivo físico: ' . public_path($imageRecord->path));
                    $deleted = unlink(public_path($imageRecord->path));
                    \Log::info('Archivo físico eliminado: ' . ($deleted ? 'SÍ' : 'NO'));
                } else {
                    \Log::info('No hay archivo físico para eliminar o no está en uploads');
                }
                
                // Eliminar registro de la base de datos
                \Log::info('Eliminando registro de BD con ID: ' . $imageRecord->id);
                $imageRecord->delete();
                \Log::info('Registro de BD eliminado exitosamente');
                
                \Log::info('=== FIN deleteImage EXITOSO ===');
                return response()->json([
                    'success' => true, 
                    'message' => 'Imagen restaurada a la original exitosamente'
                ]);
            } else {
                \Log::info('No se encontró registro para eliminar con key: ' . $key);
                \Log::info('=== FIN deleteImage - Sin registro ===');
                return response()->json([
                    'success' => true, 
                    'message' => 'No hay imagen personalizada para eliminar'
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('ERROR en deleteImage: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::info('=== FIN deleteImage CON ERROR ===');
            
            return response()->json([
                'error' => 'Error al eliminar imagen: ' . $e->getMessage()
            ], 500);
        }
    }
}
