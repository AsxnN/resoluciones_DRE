<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->id('id_persona');
            $table->enum('tipo_persona', ['cliente', 'colaborador']);
            $table->string('tipo_documento', 20);
            $table->string('num_documento', 20);
            $table->string('nombres', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100)->nullable();
            $table->string('correo', 100)->nullable()->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->text('direccion')->nullable();
            $table->boolean('datos_completos')->default(false);
            $table->boolean('i_active')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->unique(['tipo_documento', 'num_documento']);
            $table->index('tipo_persona');
            $table->index('num_documento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};