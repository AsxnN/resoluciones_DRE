<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones_cliente', function (Blueprint $table) {
            $table->id('id_sesion');
            $table->foreignId('id_cliente')
                  ->constrained('cliente', 'id_cliente')
                  ->onDelete('cascade')
                  ->comment('Cliente que inicia sesión');
            $table->string('token_sesion', 100)->unique()->comment('Token único de sesión');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_expiracion')->comment('Fecha de expiración del token');
            $table->boolean('i_activo')->default(true);
            $table->timestamp('fecha_cierre')->nullable();
            
            $table->index('token_sesion');
            $table->index(['id_cliente', 'i_activo']);
            $table->index('fecha_expiracion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones_cliente');
    }
};
