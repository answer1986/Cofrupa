<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ContactController;

Route::get('/', function () {
    return view('index');
});

Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');

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
    
    // Rutas de contactos
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('contacts/import', [ContactController::class, 'importExisting'])->name('contacts.import');
    Route::post('footer/import', [ContactController::class, 'importFooter'])->name('footer.import');
    
    // Rutas del carrusel
    Route::get('carousel', [App\Http\Controllers\Admin\CarouselController::class, 'index'])->name('carousel.index');
    Route::get('carousel/get/{id}', [App\Http\Controllers\Admin\CarouselController::class, 'show'])->name('carousel.get');
    Route::post('carousel/store', [App\Http\Controllers\Admin\CarouselController::class, 'store'])->name('carousel.store');
    Route::post('carousel/update/{id}', [App\Http\Controllers\Admin\CarouselController::class, 'update'])->name('carousel.update');
    Route::post('carousel/destroy', [App\Http\Controllers\Admin\CarouselController::class, 'destroy'])->name('carousel.destroy');
    Route::post('carousel/reorder', [App\Http\Controllers\Admin\CarouselController::class, 'reorder'])->name('carousel.reorder');
    Route::post('carousel/update-order', [App\Http\Controllers\Admin\CarouselController::class, 'updateCarouselOrder'])->name('carousel.update-order');
    
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