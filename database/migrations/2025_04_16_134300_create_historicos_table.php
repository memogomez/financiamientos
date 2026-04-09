<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    protected $primaryKey = 'id_historico';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historicos', function (Blueprint $table) {
            $table->bigIncrements('id_historico');
            $table->string('fecha');
            $table->string('nombre_autorizo');
            $table->string('ticket');
            $table->string('nombre_solicitante');
            $table->string('plaza');
            $table->string('gafete');
            $table->string('agencia_mp');
            $table->string('turno');
            $table->string('acronimo');
            $table->string('hora');
            $table->string('dia_mes');
            $table->string('anio');
            $table->string('consecutivo');
            $table->string('nombre_proporciona');
            $table->string('razon', 2500);
            $table->string('numero_registro');
            $table->string('detenido', 1500);
            $table->string('delito', 2500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historicos');
    }
};
