<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resolucion', function (Blueprint $table) {
            $table->foreignId('id_persona_entrega')->nullable()->after('id_usuario_firma')
                ->constrained('persona', 'id_persona')->nullOnDelete()->cascadeOnUpdate();
            $table->string('correo_entrega', 255)->nullable()->after('id_persona_entrega');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resolucion', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_persona_entrega');
            $table->dropColumn('correo_entrega');
        });
    }
};
