<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Si las tablas ya existen en la base de datos (p. ej. creadas manualmente), no se vuelven a crear.
     */
    public function up(): void
    {
        if (! Schema::hasTable('areas')) {
            Schema::create('areas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre_area', 150);
                $table->timestamps();
                $table->integer('estatus')->default(1);
            });
        }

        if (! Schema::hasTable('solicitudes')) {
            Schema::create('solicitudes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_area')->constrained('areas')->cascadeOnUpdate()->restrictOnDelete();
                $table->foreignId('id_usuario')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
                $table->date('fecha');
                $table->string('solicita', 255);
                $table->string('dirigido', 255);
                $table->string('mes', 20);
                $table->decimal('monto_solicitado', 12, 2)->default(0);
                $table->decimal('monto_total', 12, 2)->default(0);
                $table->decimal('total', 12, 2)->default(0);
                $table->boolean('comprobacion')->default(false);
                $table->integer('estatus')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
        Schema::dropIfExists('areas');
    }
};
