<?php
// filepath: database/migrations/2026_01_17_000000_create_envios_credenciales_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('envios_credenciales', function (Blueprint $table) {
            $table->id('id_envio_credencial');
            
            // Relación con persona_resolucion_datos
            $table->unsignedBigInteger('id_persona_resolucion_datos');
            $table->foreign('id_persona_resolucion_datos')
                  ->references('id_persona_resolucion_datos')
                  ->on('persona_resolucion_datos')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            
            // Correos
            $table->string('correo_destino', 255); // Email donde se enviaron las credenciales
            $table->string('correo_sistema_generado', 100); // Email del sistema (jalcsar@dre.com)
            
            // Seguridad
            $table->string('password_generado_hash'); // Contraseña hasheada (para auditoría)
            
            // Usuario que envió
            $table->unsignedBigInteger('id_usuario_envia');
            $table->foreign('id_usuario_envia')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnUpdate();
            
            // Estado del envío
            $table->enum('estado_envio', ['enviado', 'fallido'])->default('enviado');
            $table->text('observaciones')->nullable();
            $table->string('ip_address', 45)->nullable();
            
            // Timestamps
            $table->timestamp('fecha_envio')->useCurrent();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            // Índices
            $table->index('id_persona_resolucion_datos');
            $table->index('correo_destino');
            $table->index('correo_sistema_generado');
            $table->index('id_usuario_envia');
            $table->index('estado_envio');
            $table->index('fecha_envio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('envios_credenciales');
    }
};