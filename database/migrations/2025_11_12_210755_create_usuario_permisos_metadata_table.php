<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        
        Schema::create('usuario_permisos_metadata', function (Blueprint $table) use ($tableNames) {
            $table->id('id_metadata');
            $table->foreignId('id_usuario')
                  ->constrained('users', 'id')
                  ->onDelete('cascade')
                  ->comment('Usuario al que se le asigna el permiso');
            $table->unsignedBigInteger('permission_id')
                  ->comment('ID del permiso de Spatie');
            $table->foreignId('id_usuario_asigna')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->onDelete('set null')
                  ->comment('Admin que asignó el permiso');
            $table->text('observacion')->nullable();
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamp('fecha_revocacion')->nullable();
            $table->boolean('i_active')->default(true);
            
            $table->unique(['id_usuario', 'permission_id'], 'usuario_permiso_metadata_unique');
            $table->index(['id_usuario', 'i_active']);
            $table->index('permission_id');
            
            // FK con permissions de Spatie
            $table->foreign('permission_id')
                  ->references('id')
                  ->on($tableNames['permissions'])
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_permisos_metadata');
    }
};