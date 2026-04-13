<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn(['monto_total', 'total']);
            $table->text('observaciones')->nullable()->after('monto_solicitado');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn('observaciones');
            $table->decimal('monto_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
        });
    }
};
