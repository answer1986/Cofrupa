<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::orderBy('section')->orderBy('key')->paginate(20);
        return view('admin.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:images',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:10240',
            'alt_text_es' => 'nullable|string',
            'alt_text_en' => 'nullable|string',
            'section' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $image = $request->file('image');
        $fileName = time() . '_' . Str::slug($request->key) . '.' . $image->getClientOriginalExtension();
        $path = 'image/admin/' . $fileName;
        
        // Mover la imagen al directorio público
        $image->move(public_path('image/admin'), $fileName);

        Image::create([
            'key' => $request->key,
            'path' => $path,
            'alt_text_es' => $request->alt_text_es,
            'alt_text_en' => $request->alt_text_en,
            'section' => $request->section,
            'description' => $request->description,
            'is_active' => true
        ]);

        return redirect()->route('admin.images.index')
                        ->with('success', 'Imagen subida exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return view('admin.images.show', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        return view('admin.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        $request->validate([
            'key' => 'required|string|unique:images,key,' . $image->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:10240',
            'alt_text_es' => 'nullable|string',
            'alt_text_en' => 'nullable|string',
            'section' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $updateData = [
            'key' => $request->key,
            'alt_text_es' => $request->alt_text_es,
            'alt_text_en' => $request->alt_text_en,
            'section' => $request->section,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ];

        // Si se sube una nueva imagen
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior
            if (file_exists(public_path($image->path))) {
                unlink(public_path($image->path));
            }

            $imageFile = $request->file('image');
            $fileName = time() . '_' . Str::slug($request->key) . '.' . $imageFile->getClientOriginalExtension();
            $path = 'image/admin/' . $fileName;
            
            $imageFile->move(public_path('image/admin'), $fileName);
            $updateData['path'] = $path;
        }

        $image->update($updateData);

        return redirect()->route('admin.images.index')
                        ->with('success', 'Imagen actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        // Eliminar el archivo físico
        if (file_exists(public_path($image->path))) {
            unlink(public_path($image->path));
        }

        $image->delete();

        return redirect()->route('admin.images.index')
                        ->with('success', 'Imagen eliminada exitosamente');
    }

    public function importExisting()
    {
        // Importar imágenes existentes del directorio público
        $existingImages = [
            ['key' => 'header_logo', 'path' => 'image/Onizzo-header.png', 'section' => 'header', 'description' => 'Logo del header'],
            ['key' => 'footer_logo', 'path' => 'image/onizzo-footer2.png', 'section' => 'footer', 'description' => 'Logo del footer'],
            ['key' => 'product_ciruelas', 'path' => 'image/productos/ciruelas.png', 'section' => 'productos', 'description' => 'Imagen de ciruelas'],
            ['key' => 'product_ajos', 'path' => 'image/productos/ajos.png', 'section' => 'productos', 'description' => 'Imagen de ajos'],
            ['key' => 'product_guinda', 'path' => 'image/productos/guinda.png', 'section' => 'productos', 'description' => 'Imagen de guindas'],
            ['key' => 'product_nueces', 'path' => 'image/productos/nueces.png', 'section' => 'productos', 'description' => 'Imagen de nueces'],
            ['key' => 'carousel_frutas_secas', 'path' => 'image/frutas secas.jpeg', 'section' => 'nosotros', 'description' => 'Carrusel - Frutas secas'],
            ['key' => 'carousel_pasas', 'path' => 'image/pasas.jpeg', 'section' => 'nosotros', 'description' => 'Carrusel - Pasas'],
            ['key' => 'carousel_ciruela', 'path' => 'image/ciruela.png', 'section' => 'nosotros', 'description' => 'Carrusel - Ciruela'],
            ['key' => 'carousel_jefe', 'path' => 'image/Jefe.jpeg', 'section' => 'nosotros', 'description' => 'Carrusel - Jefe'],
        ];

        foreach ($existingImages as $imageData) {
            if (file_exists(public_path($imageData['path']))) {
                Image::firstOrCreate(
                    ['key' => $imageData['key']],
                    array_merge($imageData, [
                        'alt_text_es' => $imageData['description'],
                        'alt_text_en' => $imageData['description'],
                        'is_active' => true
                    ])
                );
            }
        }

        return redirect()->route('admin.images.index')
                        ->with('success', 'Imágenes existentes importadas exitosamente');
    }
}
