<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historial_firma', function (Blueprint $table) {
            $table->enum('metodo_firma', ['firma_peru', 'firma_local', 'otro'])
                  ->default('firma_peru')
                  ->after('id_usuario');
            $table->json('certificado_digital')->nullable()
                  ->after('metodo_firma')
                  ->comment('Información del certificado digital usado');
            $table->string('hash_documento', 255)->nullable()
                  ->after('certificado_digital')
                  ->comment('Hash SHA256 del documento original');
            $table->string('hash_firmado', 255)->nullable()
                  ->after('hash_documento')
                  ->comment('Hash SHA256 del documento firmado');
            $table->ipAddress('ip_firmante')->nullable()
                  ->after('hash_firmado')
                  ->comment('IP desde donde se firmó');
            
            $table->index('metodo_firma');
            $table->index('hash_documento');
        });
    }

    public function down(): void
    {
        Schema::table('historial_firma', function (Blueprint $table) {
            $table->dropIndex(['metodo_firma']);
            $table->dropIndex(['hash_documento']);
            $table->dropColumn([
                'metodo_firma',
                'certificado_digital',
                'hash_documento',
                'hash_firmado',
                'ip_firmante'
            ]);
        });
    }
};