<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCertOrderToImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->integer('cert_order')->nullable()->after('carousel_order');
        });
        
        // Actualizar las certificaciones existentes con orden
        DB::table('images')
            ->where('section', 'certificaciones')
            ->where('key', 'cert_1')
            ->update(['cert_order' => 1]);
            
        DB::table('images')
            ->where('section', 'certificaciones')
            ->where('key', 'cert_2')
            ->update(['cert_order' => 2]);
            
        DB::table('images')
            ->where('section', 'certificaciones')
            ->where('key', 'cert_3')
            ->update(['cert_order' => 3]);
            
        DB::table('images')
            ->where('section', 'certificaciones')
            ->where('key', 'cert_4')
            ->update(['cert_order' => 4]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('cert_order');
        });
    }
}
