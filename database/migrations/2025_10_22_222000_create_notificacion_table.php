<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificacion', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->foreignId('id_resolucion')->constrained('resolucion', 'id_resolucion')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_usuario')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('tipo_notificacion', ['resolucion_nueva', 'resolucion_firmada', 'resolucion_enviada', 'mencion']);
            $table->text('mensaje')->nullable();
            $table->boolean('i_leido')->default(false);
            $table->dateTime('fecha_lectura')->nullable();
            $table->timestamp('fecha_notificacion')->useCurrent();
            
            $table->index('id_resolucion');
            $table->index('id_usuario');
            $table->index('i_leido');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacion');
    }
};