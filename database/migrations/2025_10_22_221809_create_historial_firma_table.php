<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_firma', function (Blueprint $table) {
            $table->id('id_historial_firma');
            $table->foreignId('id_resolucion')->constrained('resolucion', 'id_resolucion')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_usuario')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('archivo_antes_firma', 255)->nullable();
            $table->string('archivo_despues_firma', 255)->nullable();
            $table->string('estado_firmaperu', 50)->nullable();
            $table->text('respuesta_firmaperu')->nullable();
            $table->dateTime('fecha_envio_firmaperu')->nullable();
            $table->dateTime('fecha_respuesta_firmaperu')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            
            $table->index('id_resolucion');
            $table->index('id_usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_firma');
    }
};