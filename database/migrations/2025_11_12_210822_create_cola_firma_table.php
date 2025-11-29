<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cola_firma', function (Blueprint $table) {
            $table->id('id_cola_firma');
            $table->foreignId('id_resolucion')
                  ->constrained('resolucion', 'id_resolucion')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreignId('id_usuario_solicita')
                  ->constrained('users', 'id')
                  ->onDelete('cascade')
                  ->comment('Usuario que solicita la firma');
            $table->foreignId('id_usuario_firmante')
                  ->constrained('users', 'id')
                  ->onDelete('cascade')
                  ->comment('Usuario que debe firmar');
            $table->foreignId('id_estado_firma')
                  ->constrained('estados_firma', 'id_estado_firma')
                  ->onUpdate('cascade');
            $table->enum('prioridad', ['alta', 'media', 'baja'])->default('media');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->dateTime('fecha_limite')->nullable();
            $table->dateTime('fecha_firma')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('motivo_rechazo')->nullable();
            
            $table->index(['id_estado_firma', 'prioridad']);
            $table->index('id_usuario_firmante');
            $table->index('fecha_limite');
            $table->index(['id_resolucion', 'id_estado_firma']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cola_firma');
    }
};