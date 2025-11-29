<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria', function (Blueprint $table) {
            $table->id('id_auditoria');
            $table->unsignedBigInteger('id_usuario')->nullable();
            
            // ✅ CORREGIDO: Aumentar longitud
            $table->string('accion', 50);
            
            $table->string('tabla_afectada', 100)->nullable();
            $table->unsignedBigInteger('id_registro')->nullable();
            $table->text('descripcion')->nullable();
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('fecha_accion')->useCurrent();
            
            // Foreign Key
            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Índices para optimización
            $table->index(['id_usuario', 'fecha_accion']);
            $table->index('tabla_afectada');
            $table->index('accion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria');
    }
};