<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persona_resolucion_datos', function (Blueprint $table) {
            $table->boolean('asignado_a_cliente')->default(false)->after('es_interna');
            $table->timestamp('fecha_asignacion')->nullable()->after('asignado_a_cliente');
        });
    }

    public function down(): void
    {
        Schema::table('persona_resolucion_datos', function (Blueprint $table) {
            $table->dropColumn(['asignado_a_cliente', 'fecha_asignacion']);
        });
    }
};