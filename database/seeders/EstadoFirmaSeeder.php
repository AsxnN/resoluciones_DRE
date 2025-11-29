<?php
// filepath: database/seeders/EstadoFirmaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoFirmaSeeder extends Seeder
{
    public function run(): void
    {
        $estadosFirma = [
            [
                'nombre_estado' => 'Pendiente',
                'descripcion' => 'Solicitud pendiente',
                'color' => '#f59e0b',
                'i_active' => true,
            ],
            [
                'nombre_estado' => 'En Proceso',
                'descripcion' => 'Usuario revisando documento',
                'color' => '#3b82f6',
                'i_active' => true,
            ],
            [
                'nombre_estado' => 'Firmado',
                'descripcion' => 'Documento firmado',
                'color' => '#10b981',
                'i_active' => true,
            ],
            [
                'nombre_estado' => 'Rechazado',
                'descripcion' => 'Solicitud rechazada',
                'color' => '#ef4444',
                'i_active' => true,
            ],
            [
                'nombre_estado' => 'Expirado',
                'descripcion' => 'Tiempo de firma expirado',
                'color' => '#64748b',
                'i_active' => true,
            ],
            [
                'nombre_estado' => 'Cancelado',
                'descripcion' => 'Solicitud cancelada',
                'color' => '#94a3b8',
                'i_active' => true,
            ],
        ];

        DB::table('estados_firma')->insert($estadosFirma);

        $this->command->info('✅ 6 estados de firma creados');
    }
}