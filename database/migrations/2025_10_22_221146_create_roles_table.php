<?php
// filepath: database/migrations/2025_10_22_221146_create_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cambiar nombre a roles_organizacionales para no conflictuar con Spatie
        Schema::create('roles_organizacionales', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('nombre_rol', 100)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->boolean('i_active')->default(true);
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('nombre_rol');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles_organizacionales');
    }
};