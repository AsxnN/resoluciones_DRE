<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id('id_modulo');
            $table->string('nombre_modulo', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->string('ruta', 255)->nullable();
            $table->string('icono', 50)->nullable();
            $table->integer('orden')->default(0);
            $table->enum('tipo_modulo', ['admin', 'colaborador', 'compartido'])->default('colaborador');
            $table->boolean('i_active')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('slug');
            $table->index('tipo_modulo');
            $table->index('i_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};