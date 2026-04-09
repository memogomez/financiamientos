<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{   

    protected $primaryKey = 'id_folio';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('folios', function (Blueprint $table) {
            $table->bigIncrements('id_folio');
            $table->unsignedBigInteger('id_solicitante');
            $table->foreign('id_solicitante')->references('id_solicitante')->on('solicitantes')->onDelete('cascade');
            $table->string('ticket')->nullable();
            $table->string('acronimo');
            $table->string('hora');
            $table->string('dia_mes');
            $table->string('anio');
            $table->string('folio')->nullable();
            $table->text('razon');
            $table->string('numero_registro');
            $table->unsignedBigInteger('id_delito');
            $table->foreign('id_delito')->references('id_delito')->on('delitos')->onDelete('cascade');
            $table->string('detenido');
            $table->timestamps();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folios');
    }
};
