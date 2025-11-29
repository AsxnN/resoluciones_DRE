<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estados_firma', function (Blueprint $table) {
            $table->id('id_estado_firma');
            $table->string('nombre_estado', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->string('color', 20)->default('gray')->comment('Color para UI: yellow, blue, green, red, gray');
            $table->boolean('i_active')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            
            $table->index('nombre_estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estados_firma');
    }
};