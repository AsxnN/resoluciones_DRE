<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colaborador', function (Blueprint $table) {
            $table->string('id_colab_dis', 50)->primary();
            $table->foreignId('id_persona')->unique()->constrained('persona', 'id_persona')->cascadeOnDelete()->cascadeOnUpdate();
            
            // ROL ORGANIZACIONAL MOVIDO A TABLA USERS
            
            $table->foreignId('id_cargos')->nullable()->constrained('cargo', 'id_cargos')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_unidad')->nullable()->constrained('unidad', 'id_unidad')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_direcciones')->nullable()->constrained('direccion', 'id_direcciones')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_dependencia')->nullable()->constrained('dependencia', 'id_dependencias')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_area')->nullable()->constrained('area', 'id_area')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_especialidad')->nullable()->constrained('especialidad', 'id_especialidad')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_tipo_personal')->nullable()->constrained('tipo_personal', 'id_tipo_personal')->nullOnDelete()->cascadeOnUpdate();
            $table->boolean('i_active')->default(true);
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('id_persona');
            $table->index('id_cargos');
            $table->index('id_unidad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colaborador');
    }
};