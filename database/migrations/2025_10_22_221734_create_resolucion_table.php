<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resolucion', function (Blueprint $table) {
            $table->id('id_resolucion');
            $table->foreignId('id_estado')->constrained('estado', 'id_estado')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_tipo_resolucion')->constrained('tipo_resolucion', 'id_tipo_resolucion')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('num_resolucion', 50)->unique();
            $table->date('fecha_resolucion');
            $table->text('visto_resolucion')->nullable();
            $table->text('asunto_resolucion')->nullable();
            $table->string('archivo_resolucion', 255)->nullable();
            $table->string('archivo_firmado', 255)->nullable();
            $table->dateTime('fecha_firma')->nullable();
            $table->foreignId('id_usuario_firma')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->boolean('enviada_resolucion')->default(false);
            $table->dateTime('fecha_envio')->nullable();
            $table->boolean('i_active')->default(true);
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('id_estado');
            $table->index('id_tipo_resolucion');
            $table->index('num_resolucion');
            $table->index('fecha_resolucion');
            $table->index('id_usuario_firma');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resolucion');
    }
};