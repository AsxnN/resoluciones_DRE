<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_chunks', function (Blueprint $table) {
            $table->id('id_chunk');
            $table->foreignId('id_documento_texto')
                  ->constrained('documento_texto_ocr', 'id_documento_texto')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->integer('chunk_index')->comment('Índice del chunk (0, 1, 2...)');
            $table->text('contenido_chunk');
            $table->json('metadata_chunk')->nullable()->comment('Página, sección, etc.');
            $table->json('embedding')->nullable()->comment('Vector embeddings para búsqueda semántica');
            $table->integer('num_tokens')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            
            $table->index(['id_documento_texto', 'chunk_index']);
            $table->index('chunk_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_chunks');
    }
};