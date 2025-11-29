<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persona_resolucion', function (Blueprint $table) {
            $table->id('id_persona_resolucion');
            $table->foreignId('id_resolucion')->constrained('resolucion', 'id_resolucion')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_persona')->constrained('persona', 'id_persona')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('tipo_relacion', ['involucrado', 'notificado', 'firmante'])->default('involucrado');
            $table->boolean('i_notificado')->default(false);
            $table->dateTime('fecha_notificacion')->nullable();
            $table->boolean('i_active')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->unique(['id_resolucion', 'id_persona'], 'unique_persona_resolucion');
            $table->index('id_resolucion');
            $table->index('id_persona');
            $table->index('tipo_relacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona_resolucion');
    }
};