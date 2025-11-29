<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consulta_asistente', function (Blueprint $table) {
            $table->id('id_consulta');
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_resolucion')->nullable()->constrained('resolucion', 'id_resolucion')->nullOnDelete()->cascadeOnUpdate();
            $table->text('pregunta');
            $table->text('respuesta');
            $table->text('contexto_usado')->nullable();
            $table->integer('tiempo_respuesta_ms')->nullable();
            $table->tinyInteger('valoracion')->nullable();
            $table->timestamp('fecha_consulta')->useCurrent();
            
            $table->index('id_usuario');
            $table->index('id_resolucion');
            $table->index('fecha_consulta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consulta_asistente');
    }
};