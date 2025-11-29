<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_resolucion', function (Blueprint $table) {
            $table->id('id_tipo_resolucion');
            $table->string('nombre_tipo_resolucion', 100)->unique();
            $table->boolean('i_active')->default(true);
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('nombre_tipo_resolucion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_resolucion');
    }
};