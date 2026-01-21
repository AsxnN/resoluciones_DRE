<?php
// filepath: database/migrations/2026_01_19_create_registro_firma_entrega_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_firma_entrega', function (Blueprint $table) {
            $table->id('id_registro_firma_entrega');
            
            // Relación con resolución
            $table->foreignId('id_resolucion')
                  ->constrained('resolucion', 'id_resolucion')
                  ->onDelete('cascade')
                  ->comment('Resolución que se firma para entregar');
            
            // Relación con persona externa destinataria
            $table->foreignId('id_persona_resolucion_datos')
                  ->constrained('persona_resolucion_datos', 'id_persona_resolucion_datos')
                  ->onDelete('cascade')
                  ->comment('Persona externa que solicita/recibe la resolución');
            
            // Usuario que realiza la firma (trabajador DRE)
            $table->foreignId('id_usuario_firmante')
                  ->constrained('users', 'id')
                  ->onDelete('cascade')
                  ->comment('Usuario DRE que firma con Firma Perú');
            
            // Usuario que solicita la firma (puede ser el mismo o el creador)
            $table->foreignId('id_usuario_solicita')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->onDelete('set null')
                  ->comment('Usuario que solicita la firma para entrega');
            
            // Estado de la firma (booleano por ahora hasta integrar Firma Perú)
            $table->boolean('firmado')->default(false)->comment('Estado de firma (manual por ahora)');
            
            // Fechas
            $table->timestamp('fecha_solicitud')->useCurrent()->comment('Cuándo se solicitó la firma');
            $table->timestamp('fecha_firma')->nullable()->comment('Cuándo se firmó con Firma Perú');
            $table->timestamp('fecha_entrega')->nullable()->comment('Cuándo se entregó al cliente');
            
            // Archivos
            $table->string('archivo_firmado_entrega', 500)->nullable()->comment('Ruta del archivo firmado para esta entrega específica');
            
            // Datos adicionales
            $table->string('codigo_verificacion', 100)->nullable()->unique()->comment('Código único para verificar firma');
            $table->text('observaciones')->nullable()->comment('Notas sobre la firma/entrega');
            $table->string('correo_destino', 255)->nullable()->comment('Correo donde se envió la resolución');
            
            // Metadata de Firma Perú (para cuando se integre)
            $table->json('metadata_firma_peru')->nullable()->comment('Datos de respuesta de Firma Perú API');
            
            // Timestamps
            $table->timestamps();
            
            // Índices con nombres cortos
            $table->index('id_resolucion', 'idx_rfe_resolucion');
            $table->index('id_persona_resolucion_datos', 'idx_rfe_persona');
            $table->index('id_usuario_firmante', 'idx_rfe_firmante');
            $table->index('fecha_solicitud', 'idx_rfe_fecha_sol');
            $table->index('firmado', 'idx_rfe_firmado');
            $table->index(['id_resolucion', 'id_persona_resolucion_datos', 'fecha_solicitud'], 'idx_rfe_res_per_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_firma_entrega');
    }
};