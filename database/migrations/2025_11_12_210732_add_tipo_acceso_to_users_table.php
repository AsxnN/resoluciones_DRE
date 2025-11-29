<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tipo de acceso al sistema
            $table->enum('tipo_acceso', ['admin', 'colaborador', 'cliente'])
                  ->default('colaborador')
                  ->after('id_persona')
                  ->comment('Tipo de acceso: admin (panel privilegios), colaborador (panel completo), cliente (solo lectura)');
            
            // Email alternativo (opcional para clientes)
            $table->string('email_alternativo', 100)->nullable()->after('email');
            
            // Última sesión
            $table->timestamp('ultima_sesion')->nullable()->after('i_active');
            
            $table->index('tipo_acceso');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['tipo_acceso']);
            $table->dropColumn(['tipo_acceso', 'email_alternativo', 'ultima_sesion']);
        });
    }
};