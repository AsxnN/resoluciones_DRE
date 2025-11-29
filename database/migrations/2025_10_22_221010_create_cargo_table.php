<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargo', function (Blueprint $table) {
            $table->id('id_cargos');
            $table->string('nombre_cargo', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('i_active')->default(true);
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('nombre_cargo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargo');
    }
};