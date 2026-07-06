<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            $table->boolean('obtenido_reniec')->default(false)->after('datos_completos');
            $table->dropColumn('whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            $table->dropColumn('obtenido_reniec');
            $table->string('whatsapp', 20)->nullable()->after('telefono');
        });
    }
};
