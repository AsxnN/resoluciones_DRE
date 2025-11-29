<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('id_persona')->nullable()->after('id')->unique()->constrained('persona', 'id_persona')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('i_active')->default(true)->after('profile_photo_path');
            
            // NO agregamos id_rol porque usaremos Spatie
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_persona']);
            $table->dropColumn(['id_persona', 'i_active']);
        });
    }
};