<?php
// filepath: database/seeders/ModuloSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            // MÓDULO ADMIN
            [
                'nombre_modulo' => 'Gestión de Privilegios',
                'slug' => 'gestion-privilegios',
                'descripcion' => 'Asignación y revocación de permisos a usuarios colaboradores',
                'ruta' => '/admin/privilegios',
                'icono' => 'fas fa-user-shield',
                'orden' => 0,
                'tipo_modulo' => 'admin',
                'i_active' => true,
            ],

            // MÓDULOS COLABORADOR
            [
                'nombre_modulo' => 'Personas',
                'slug' => 'personas',
                'descripcion' => 'Gestión de personas (clientes y colaboradores)',
                'ruta' => '/colaborador/personas',
                'icono' => 'fas fa-users',
                'orden' => 1,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Resoluciones',
                'slug' => 'resoluciones',
                'descripcion' => 'CRUD de resoluciones y gestión de firma digital',
                'ruta' => '/colaborador/resoluciones',
                'icono' => 'fas fa-file-alt',
                'orden' => 2,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Resoluciones Firmadas',
                'slug' => 'resoluciones-firmadas',
                'descripcion' => 'Consulta de resoluciones con firma digital aprobada',
                'ruta' => '/colaborador/resoluciones-firmadas',
                'icono' => 'fas fa-stamp',
                'orden' => 3,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Áreas',
                'slug' => 'areas',
                'descripcion' => 'Gestión de áreas funcionales',
                'ruta' => '/colaborador/areas',
                'icono' => 'fas fa-sitemap',
                'orden' => 4,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Cargos',
                'slug' => 'cargos',
                'descripcion' => 'Gestión de cargos laborales',
                'ruta' => '/colaborador/cargos',
                'icono' => 'fas fa-briefcase',
                'orden' => 5,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Colaboradores',
                'slug' => 'colaboradores',
                'descripcion' => 'Gestión de colaboradores DRE',
                'ruta' => '/colaborador/colaboradores',
                'icono' => 'fas fa-user-tie',
                'orden' => 6,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Dependencias',
                'slug' => 'dependencias',
                'descripcion' => 'Gestión de dependencias institucionales',
                'ruta' => '/colaborador/dependencias',
                'icono' => 'fas fa-building',
                'orden' => 7,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Direcciones',
                'slug' => 'direcciones',
                'descripcion' => 'Gestión de direcciones (DGP, DIGETE, DRE, UGEL)',
                'ruta' => '/colaborador/direcciones',
                'icono' => 'fas fa-map-marked-alt',
                'orden' => 8,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Especialidades',
                'slug' => 'especialidades',
                'descripcion' => 'Gestión de especialidades profesionales',
                'ruta' => '/colaborador/especialidades',
                'icono' => 'fas fa-graduation-cap',
                'orden' => 9,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Tipos de Personal',
                'slug' => 'tipos-personal',
                'descripcion' => 'Gestión de tipos de personal',
                'ruta' => '/colaborador/tipos-personal',
                'icono' => 'fas fa-id-badge',
                'orden' => 10,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
            [
                'nombre_modulo' => 'Usuarios',
                'slug' => 'usuarios',
                'descripcion' => 'Gestión de usuarios del sistema',
                'ruta' => '/colaborador/usuarios',
                'icono' => 'fas fa-user-cog',
                'orden' => 11,
                'tipo_modulo' => 'colaborador',
                'i_active' => true,
            ],
        ];

        DB::table('modulos')->insert($modulos);

        $this->command->info('✅ 13 módulos creados');
    }
}