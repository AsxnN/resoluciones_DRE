<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entrega_resolucion', function (Blueprint $table) {
            $table->id('id_entrega_resolucion');
            $table->foreignId('id_resolucion')->constrained('resolucion', 'id_resolucion')->cascadeOnDelete();
            $table->foreignId('id_persona_entrega')->constrained('persona', 'id_persona')->cascadeOnDelete();
            $table->string('correo_entrega', 255)->nullable();
            $table->string('archivo_firmado', 255);
            $table->foreignId('id_usuario_firma')->constrained('users')->cascadeOnDelete();
            $table->boolean('cuenta_creada')->default(false);
            $table->timestamp('fecha_entrega')->useCurrent();

            $table->index('id_resolucion');
            $table->index('id_persona_entrega');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_resolucion');
    }
};
