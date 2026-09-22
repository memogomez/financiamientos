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
        Schema::table('archivos', function (Blueprint $table) {
            $table->string('numero_oficio')->unique()->after('fecha_archivo');
            $table->unsignedInteger('id_area')->nullable()->after('numero_oficio');
            $table->foreign('id_area')->references('id')->on('areas')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archivos', function (Blueprint $table) {
            $table->dropForeign(['id_area']);
            $table->dropColumn('id_area');
            $table->dropUnique('archivos_numero_oficio_unique');
            $table->dropColumn('numero_oficio');
        });
    }
};
