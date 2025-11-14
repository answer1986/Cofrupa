<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
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
        $contents = Content::orderBy('section')->orderBy('key')->paginate(20);
        return view('admin.contents.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.contents.create');
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
            'key' => 'required|string|unique:contents',
            'value_es' => 'nullable|string',
            'value_en' => 'nullable|string',
            'section' => 'required|string',
            'type' => 'required|in:text,textarea,html',
            'description' => 'nullable|string'
        ]);

        Content::create($request->all());

        return redirect()->route('admin.contents.index')
                        ->with('success', 'Contenido creado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        return view('admin.contents.show', compact('content'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content)
    {
        return view('admin.contents.edit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content)
    {
        $request->validate([
            'key' => 'required|string|unique:contents,key,' . $content->id,
            'value_es' => 'nullable|string',
            'value_en' => 'nullable|string',
            'section' => 'required|string',
            'type' => 'required|in:text,textarea,html',
            'description' => 'nullable|string'
        ]);

        $content->update($request->all());

        return redirect()->route('admin.contents.index')
                        ->with('success', 'Contenido actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Content $content)
    {
        $content->delete();

        return redirect()->route('admin.contents.index')
                        ->with('success', 'Contenido eliminado exitosamente');
    }

    public function importFromMessages()
    {
        // Importar desde el archivo de mensajes existente
        $messages = include resource_path('lang/es/messages.php');
        $messagesEn = file_exists(resource_path('lang/en/messages.php')) 
                        ? include resource_path('lang/en/messages.php') 
                        : [];

        foreach ($messages as $key => $value) {
            Content::firstOrCreate(
                ['key' => $key],
                [
                    'value_es' => $value,
                    'value_en' => $messagesEn[$key] ?? $value,
                    'section' => $this->getSectionFromKey($key),
                    'type' => strlen($value) > 100 ? 'textarea' : 'text',
                    'description' => 'Importado desde messages.php'
                ]
            );
        }

        return redirect()->route('admin.contents.index')
                        ->with('success', 'Contenidos importados exitosamente');
    }

    private function getSectionFromKey($key)
    {
        if (str_contains($key, 'slider')) return 'slider';
        if (str_contains($key, 'about')) return 'nosotros';
        if (str_contains($key, 'product')) return 'productos';
        if (str_contains($key, 'contact')) return 'contacto';
        if (str_contains($key, 'market')) return 'mercados';
        return 'general';
    }
}
