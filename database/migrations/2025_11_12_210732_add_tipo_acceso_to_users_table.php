<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Solo añade tipo_acceso si no existe (la migración base ya lo incluye)
            if (!Schema::hasColumn('users', 'tipo_acceso')) {
                $table->enum('tipo_acceso', ['admin', 'colaborador', 'cliente'])
                      ->default('colaborador')
                      ->after('id_persona')
                      ->comment('Tipo de acceso: admin (panel privilegios), colaborador (panel completo), cliente (solo lectura)');
                $table->index('tipo_acceso');
            }

            // Solo añade ultima_sesion si no existe
            if (!Schema::hasColumn('users', 'ultima_sesion')) {
                $table->timestamp('ultima_sesion')->nullable()->after('i_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'tipo_acceso')) {
                $table->dropIndex(['tipo_acceso']);
                $table->dropColumn('tipo_acceso');
            }
            if (Schema::hasColumn('users', 'ultima_sesion')) {
                $table->dropColumn('ultima_sesion');
            }
        });
    }
};