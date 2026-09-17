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
        Schema::dropIfExists('paginas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('paginas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archivo_id')->constrained('archivos')->cascadeOnDelete();
            $table->unsignedInteger('numero_pagina');
            $table->longText('texto');
        });
    }
};
