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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Clave única para identificar el contenido (ej: 'about_us_title')
            $table->text('value_es')->nullable(); // Contenido en español
            $table->text('value_en')->nullable(); // Contenido en inglés
            $table->string('section')->default('general'); // Sección donde aparece (productos, nosotros, etc)
            $table->string('type')->default('text'); // Tipo de contenido (text, textarea, html)
            $table->string('description')->nullable(); // Descripción para el admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
