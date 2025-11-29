<?php
// filepath: database/seeders/EstadoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre_estado' => 'Público', 'descripcion' => 'Resolución pública'],
            ['nombre_estado' => 'Público general', 'descripcion' => 'Resolución al ppublico en general'],
            ['nombre_estado' => 'Confidencial', 'descripcion' => 'Resolución confidencial'],
        ];

        DB::table('estado')->insert($estados);

        $this->command->info('✅ 3 estados creados');
    }
}