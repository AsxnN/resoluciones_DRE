<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            // Relación con módulo (se agregará FK después de crear módulos)
            $table->unsignedBigInteger('id_modulo')->nullable()->after('id');
            
            // Slug amigable
            $table->string('slug', 100)->nullable()->after('name');
            
            // Descripción detallada
            $table->text('descripcion')->nullable()->after('guard_name');
            
            // Estado activo
            $table->boolean('i_active')->default(true)->after('descripcion');
            
            // Tipo de permiso
            $table->enum('tipo_permiso', ['crud', 'especial'])->default('crud')->after('i_active');
            
            // Índices
            $table->index('slug');
            $table->index('id_modulo');
            $table->index('i_active');
        });
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['id_modulo']);
            $table->dropIndex(['i_active']);
            $table->dropColumn(['id_modulo', 'slug', 'descripcion', 'i_active', 'tipo_permiso']);
        });
    }
};