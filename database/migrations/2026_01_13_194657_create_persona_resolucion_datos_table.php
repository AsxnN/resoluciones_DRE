<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persona_resolucion_datos', function (Blueprint $table) {
            $table->id('id_persona_resolucion_datos');
            $table->foreignId('id_resolucion')
                  ->constrained('resolucion', 'id_resolucion')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            
            // Relación con usuario (para personas internas - trabajadores DRE)
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
            
            // Datos de la persona
            $table->string('tipo_documento', 20)->default('DNI'); // DNI, CE, PASAPORTE
            $table->string('num_documento', 20);
            $table->string('nombres', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100)->nullable();
            
            // Tipo de relación con la resolución
            $table->enum('tipo_relacion', [
                'beneficiario',
                'afectado', 
                'involucrado',
                'testigo',
                'otro'
            ])->default('beneficiario');
            
            $table->string('descripcion_relacion', 255)->nullable();
            
            // Metadata
            $table->boolean('obtenido_reniec')->default(false); // Si se obtuvo de RENIEC
            $table->boolean('es_interna')->default(false); // Si es persona interna (trabajador DRE) o externa
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('id_resolucion');
            $table->index('id_user');
            $table->index('num_documento');
            $table->index('es_interna');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona_resolucion_datos');
    }
};