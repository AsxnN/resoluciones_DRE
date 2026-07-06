<?php
// filepath: database/migrations/2025_10_22_221148_add_foreign_keys_to_users_table.php
// Esta migración se ejecuta DESPUÉS de crear las tablas persona y roles_organizacionales,
// por eso los FK de users se separan de la migración original.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // FK hacia persona (creada en 2025_10_22_220858)
            $table->foreign('id_persona')
                  ->references('id_persona')
                  ->on('persona')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();

            // FK hacia roles_organizacionales (creada en 2025_10_22_221146)
            $table->foreign('id_rol')
                  ->references('id_rol')
                  ->on('roles_organizacionales')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_persona']);
            $table->dropForeign(['id_rol']);
        });
    }
};
