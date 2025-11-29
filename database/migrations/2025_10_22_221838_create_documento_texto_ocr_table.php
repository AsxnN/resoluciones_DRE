<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_texto_ocr', function (Blueprint $table) {
            $table->id('id_documento_texto');
            $table->foreignId('id_resolucion')->unique()->constrained('resolucion', 'id_resolucion')->cascadeOnDelete()->cascadeOnUpdate();
            $table->longText('texto_completo');
            $table->longText('texto_procesado')->nullable();
            $table->json('metadatos_ocr')->nullable();
            $table->enum('estado_procesamiento', ['pendiente', 'procesado', 'error'])->default('pendiente');
            $table->dateTime('fecha_procesamiento')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->fullText(['texto_completo', 'texto_procesado'], 'idx_fulltext_texto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_texto_ocr');
    }
};