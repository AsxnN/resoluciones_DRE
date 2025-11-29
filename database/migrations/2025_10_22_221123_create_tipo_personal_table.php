<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_personal', function (Blueprint $table) {
            $table->id('id_tipo_personal');
            $table->string('nombre_tipo_personal', 100)->unique();
            $table->boolean('i_active')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('nombre_tipo_personal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_personal');
    }
};