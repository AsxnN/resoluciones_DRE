<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dependencia', function (Blueprint $table) {
            $table->id('id_dependencias');
            $table->string('cod_dependencia', 20)->unique();
            $table->string('nombre_dependencia', 100);
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('i_active')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('cod_dependencia');
            $table->index('nombre_dependencia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dependencia');
    }
};