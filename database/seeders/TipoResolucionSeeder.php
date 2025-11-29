<?php
// filepath: database/seeders/TipoResolucionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoResolucionSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre_tipo_resolucion' => 'RESOLUCION DE ORGANO INSTRUCTOR', 'i_active' => true, 'id_usuario' => null],
            ['nombre_tipo_resolucion' => 'RESOLUCION DIRECTORAL REGIONAL', 'i_active' => true, 'id_usuario' => null],
            ['nombre_tipo_resolucion' => 'RESOLUCIÓN DE ORGANO SANCIONADOR', 'i_active' => true, 'id_usuario' => null],
        ];

        DB::table('tipo_resolucion')->insert($tipos);

        $this->command->info('✅ 8 tipos de resolución creados');
    }
}