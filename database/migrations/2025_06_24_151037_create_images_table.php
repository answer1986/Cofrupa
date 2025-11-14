<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Clave única para identificar la imagen (ej: 'header_logo')
            $table->string('path'); // Ruta de la imagen
            $table->string('alt_text_es')->nullable(); // Texto alternativo en español
            $table->string('alt_text_en')->nullable(); // Texto alternativo en inglés
            $table->string('section')->default('general'); // Sección donde aparece
            $table->string('description')->nullable(); // Descripción para el admin
            $table->boolean('is_active')->default(true); // Si la imagen está activa
            $table->integer('carousel_order')->nullable(); // Orden en el carrusel (NULL para no-carrusel)
            $table->integer('slider_order')->nullable(); // Orden en el slider (NULL para no-slider)
            $table->string('thumbnail_path')->nullable(); // Ruta de la imagen thumbnail para el slider
            $table->string('title_es')->nullable(); // Título en español para el slider
            $table->string('title_en')->nullable(); // Título en inglés para el slider
            $table->text('content_es')->nullable(); // Contenido en español para el slider
            $table->text('content_en')->nullable(); // Contenido en inglés para el slider
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
