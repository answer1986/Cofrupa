<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DatasheetController extends Controller
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

    public function index()
    {
        $datasheets = [];
        $path = public_path('details_product');
        
        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                if ($file->getExtension() === 'pdf') {
                    $datasheets[] = [
                        'filename' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => date('d/m/Y H:i', $file->getMTime()),
                        'url' => asset('details_product/' . $file->getFilename())
                    ];
                }
            }
        }
        
        return view('admin.datasheets.index', compact('datasheets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'datasheet' => 'required|file|mimes:pdf|max:10240' // Max 10MB
        ]);

        $file = $request->file('datasheet');
        $filename = $file->getClientOriginalName();
        
        // Limpiar nombre de archivo
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.\s]/', '', $filename);
        
        $path = public_path('details_product');
        
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        
        $file->move($path, $filename);
        
        return redirect()->route('admin.datasheets.index')
            ->with('success', 'Ficha técnica subida exitosamente.');
    }

    public function destroy($filename)
    {
        $path = public_path('details_product/' . $filename);
        
        if (File::exists($path)) {
            File::delete($path);
            return redirect()->route('admin.datasheets.index')
                ->with('success', 'Ficha técnica eliminada exitosamente.');
        }
        
        return redirect()->route('admin.datasheets.index')
            ->with('error', 'Ficha técnica no encontrada.');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}


