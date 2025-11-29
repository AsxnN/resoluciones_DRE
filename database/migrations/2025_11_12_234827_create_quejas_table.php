<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quejas', function (Blueprint $table) {
            $table->id('id_queja');
            $table->foreignId('id_cliente')
                  ->constrained('cliente', 'id_cliente')
                  ->onDelete('cascade')
                  ->comment('Cliente que realiza la queja');
            $table->foreignId('id_resolucion')
                  ->constrained('resolucion', 'id_resolucion')
                  ->onDelete('cascade')
                  ->comment('Resolución sobre la que se queja');
            $table->enum('tipo_queja', ['error_datos', 'retraso', 'incumplimiento', 'otro'])
                  ->default('otro');
            $table->text('descripcion');
            $table->enum('estado', ['pendiente', 'en_revision', 'resuelta', 'rechazada'])
                  ->default('pendiente');
            $table->foreignId('id_usuario_atiende')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->onDelete('set null')
                  ->comment('Colaborador que atiende la queja');
            $table->text('respuesta')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('estado');
            $table->index('tipo_queja');
            $table->index(['id_cliente', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quejas');
    }
};