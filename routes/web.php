<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\ImageController;

Route::get('/', function () {
    // Obtener certificaciones dinámicamente
    $certifications = \App\Models\Image::where('section', 'certificaciones')
                                       ->whereNotNull('cert_order')
                                       ->orderBy('cert_order')
                                       ->get();
    
    return view('index', compact('certifications'));
});

Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// Ruta para descargar PDFs de productos
Route::get('/download-datasheet/{filename}', function ($filename) {
    $filePath = public_path('details_product/' . $filename);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    return response()->download($filePath, $filename, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"'
    ]);
})->name('download.datasheet');

// APIs para edición inline (nuevas rutas)
Route::post('/api/admin/content/update', [AdminController::class, 'updateContent'])->name('api.content.update');
Route::post('/api/admin/image/update', [AdminController::class, 'updateImage'])->name('api.image.update');
Route::post('/api/admin/image/delete', [AdminController::class, 'deleteImage'])->name('api.image.delete');

// Ruta de prueba para debug
Route::get('/test-delete', function() {
    return view('test-delete');
});
Route::get('/test-delete-api', function() {
    \Log::info('=== TEST DELETE API CALLED ===');
    return response()->json([
        'success' => true,
        'message' => 'API funcionando',
        'session_auth' => session('admin_authenticated'),
        'method' => request()->method()
    ]);
});

// Rutas del Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Rutas de contenido
    Route::resource('contents', ContentController::class);
    Route::post('contents/import', [ContentController::class, 'importFromMessages'])->name('contents.import');
    
    // Rutas de imágenes
    Route::resource('images', ImageController::class);
    Route::post('images/import', [ImageController::class, 'importExisting'])->name('images.import');
    
    // Rutas de certificaciones
    Route::get('certifications', [App\Http\Controllers\Admin\CertificationController::class, 'index'])->name('certifications.index');
    Route::post('certifications/store', [App\Http\Controllers\Admin\CertificationController::class, 'store'])->name('certifications.store');
    Route::post('certifications/update/{id}', [App\Http\Controllers\Admin\CertificationController::class, 'update'])->name('certifications.update');
    Route::post('certifications/destroy', [App\Http\Controllers\Admin\CertificationController::class, 'destroy'])->name('certifications.destroy');
    Route::post('certifications/reorder', [App\Http\Controllers\Admin\CertificationController::class, 'reorder'])->name('certifications.reorder');
    
    // Rutas del slider
    Route::get('slider', [App\Http\Controllers\Admin\SliderController::class, 'index'])->name('slider.index');
    Route::get('slider/get/{id}', [App\Http\Controllers\Admin\SliderController::class, 'show'])->name('slider.get');
    Route::post('slider/store', [App\Http\Controllers\Admin\SliderController::class, 'store'])->name('slider.store');
    Route::post('slider/update/{id}', [App\Http\Controllers\Admin\SliderController::class, 'update'])->name('slider.update');
    Route::post('slider/destroy', [App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('slider.destroy');
    Route::post('slider/reorder', [App\Http\Controllers\Admin\SliderController::class, 'reorder'])->name('slider.reorder');
    Route::post('slider/update-order', [App\Http\Controllers\Admin\SliderController::class, 'updateSliderOrder'])->name('slider.update-order');
});

// Ruta de depuración
Route::get('/debug-lang', function () {
    dd([
        'current_locale' => app()->getLocale(),
        'available_locales' => config('app.available_locales'),
        'translation_example' => __('messages.index'),
        'session_locale' => session('locale'),
    ]);
});