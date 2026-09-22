<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('areas')->truncate();

        DB::table('areas')->insert([
            ['nombre_area' => 'AGENCIA DE INVESTIGACIÓN CRIMINAL', 'estatus' => 1],
            ['nombre_area' => 'COORDINACIÓN GENERAL DE INVESTIGACIÓN Y ANÁLISIS (CGIA)', 'estatus' => 1],
            ['nombre_area' => 'COORDINACIÓN GENERAL DE LA POLICIA DE INVESTIGACIÓN', 'estatus' => 1],
            ['nombre_area' => 'FISCALIA CENTRAL DE ATENCIÓN ESPECIALIZADA (FCAE)', 'estatus' => 1],
            ['nombre_area' => 'FISCALIA CENTRAL ESPECIALIZADA EN COMBATE A LOS DELITOS DE EXTORSION, DELITOS VINCULADOS Y SECUESTRO', 'estatus' => 1],
            ['nombre_area' => 'FISCALÍA CENTRAL PARA LA ATENCIÓN DE DELITOS VINCULADOS A LA VIOLENCIA DE GÉNERO', 'estatus' => 1],
            ['nombre_area' => 'TITULAR DE LA UNIDAD DE PROTECCIÓN DE SUJETOS QUE INTERVIENEN EN EL PROCEDIMIENTO PENAL O DE EXTINCIÓN DE DOMINIO', 'estatus' => 1],
            ['nombre_area' => 'UNIDAD DE ANÁLISIS TÁCTICO OPERATIVO  (UATO)', 'estatus' => 1],
            ['nombre_area' => 'VICEFISCALIA GENERAL', 'estatus' => 1],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('areas')->truncate();
    }
};
