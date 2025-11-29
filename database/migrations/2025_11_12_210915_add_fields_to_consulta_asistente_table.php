<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consulta_asistente', function (Blueprint $table) {
            $table->json('chunks_usados')->nullable()
                  ->after('contexto_usado')
                  ->comment('IDs de chunks usados en la respuesta');
            $table->decimal('score_relevancia', 5, 4)->nullable()
                  ->after('tiempo_respuesta_ms')
                  ->comment('Score de relevancia 0.0000 a 1.0000');
            $table->string('modelo_ia', 100)->nullable()
                  ->after('score_relevancia')
                  ->comment('Modelo de IA usado');
            $table->json('metadata_consulta')->nullable()
                  ->after('modelo_ia')
                  ->comment('Metadata adicional de la consulta');
            $table->enum('tipo_consulta', ['general', 'busqueda_resolucion', 'explicacion', 'resumen'])
                  ->default('general')
                  ->after('id_resolucion');
            
            $table->index('modelo_ia');
            $table->index('tipo_consulta');
            $table->index('score_relevancia');
        });
    }

    public function down(): void
    {
        Schema::table('consulta_asistente', function (Blueprint $table) {
            $table->dropIndex(['modelo_ia']);
            $table->dropIndex(['tipo_consulta']);
            $table->dropIndex(['score_relevancia']);
            $table->dropColumn([
                'chunks_usados',
                'score_relevancia',
                'modelo_ia',
                'metadata_consulta',
                'tipo_consulta'
            ]);
        });
    }
};