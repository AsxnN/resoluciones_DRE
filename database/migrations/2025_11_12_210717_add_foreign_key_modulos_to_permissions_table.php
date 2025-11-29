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
            $table->foreign('id_modulo')
                  ->references('id_modulo')
                  ->on('modulos')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropForeign(['id_modulo']);
        });
    }
};