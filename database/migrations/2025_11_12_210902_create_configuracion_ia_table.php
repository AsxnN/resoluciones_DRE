<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_ia', function (Blueprint $table) {
            $table->id('id_config');
            $table->string('modelo_activo', 100)->comment('llama3, mistral, gpt-4, etc.');
            $table->string('url_endpoint', 255)->nullable()->comment('URL de Ollama u otro servicio');
            $table->integer('max_tokens')->default(2048);
            $table->decimal('temperatura', 3, 2)->default(0.7)->comment('0.0 a 1.0');
            $table->integer('chunk_size')->default(500)->comment('Tamaño de chunks para RAG');
            $table->integer('chunk_overlap')->default(50)->comment('Solapamiento entre chunks');
            $table->integer('top_k')->default(5)->comment('Top K chunks relevantes');
            $table->decimal('similarity_threshold', 3, 2)->default(0.7)->comment('Umbral de similitud 0.0 a 1.0');
            $table->json('parametros_adicionales')->nullable();
            $table->boolean('i_active')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('i_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_ia');
    }
};