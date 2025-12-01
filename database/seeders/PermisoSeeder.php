<?php
// filepath: database/seeders/PermisoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        // PERMISOS ADMIN
        $moduloAdmin = DB::table('modulos')->where('slug', 'gestion-privilegios')->first();
        
        if ($moduloAdmin) {
            $this->crearPermisos($moduloAdmin->id_modulo, 'gestion-privilegios', [
                'ver' => 'Ver lista de usuarios y sus permisos',
                'gestionar' => 'Gestionar permisos de usuarios',
                'asignar' => 'Asignar permisos a usuarios',
                'revocar' => 'Revocar permisos de usuarios',
                'historial' => 'Ver historial de asignaciones',
            ]);
        }

        // PERMISOS CRUD ESTÁNDAR
        $modulosCrud = [
            'personas',
            'areas',
            'cargos',
            'colaboradores',
            'dependencias',
            'direcciones',
            'especialidades',
            'tipos-personal',
            'unidades', // ← AGREGADO
            'usuarios',
        ];

        foreach ($modulosCrud as $slugModulo) {
            $modulo = DB::table('modulos')->where('slug', $slugModulo)->first();
            
            if ($modulo) {
                $this->crearPermisosCrud($modulo->id_modulo, $slugModulo);
            }
        }

        // PERMISOS ESPECIALES: RESOLUCIONES
        $moduloResoluciones = DB::table('modulos')->where('slug', 'resoluciones')->first();
        
        if ($moduloResoluciones) {
            $this->crearPermisosCrud($moduloResoluciones->id_modulo, 'resoluciones');
            
            $this->crearPermisos($moduloResoluciones->id_modulo, 'resoluciones', [
                'firmar' => 'Firmar resoluciones digitalmente',
                'solicitar-firma' => 'Solicitar firma a otros usuarios',
                'rechazar-firma' => 'Rechazar solicitud de firma',
                'descargar-pdf' => 'Descargar PDF de resolución',
                'generar-reporte' => 'Generar reportes',
            ], 'especial');
        }

        // PERMISOS SOLO LECTURA: RESOLUCIONES FIRMADAS
        $moduloFirmadas = DB::table('modulos')->where('slug', 'resoluciones-firmadas')->first();
        
        if ($moduloFirmadas) {
            $this->crearPermisos($moduloFirmadas->id_modulo, 'resoluciones-firmadas', [
                'ver' => 'Ver lista de resoluciones firmadas',
                'detalle' => 'Ver detalle de resolución',
                'descargar' => 'Descargar resolución firmada',
                'verificar-firma' => 'Verificar autenticidad de firma',
                'exportar' => 'Exportar listado',
            ]);
        }

        $this->command->info('✅ Permisos creados');
    }

    private function crearPermisosCrud(int $idModulo, string $slug): void
    {
        $permisos = [
            'ver' => 'Ver listado',
            'crear' => 'Crear nuevo registro',
            'editar' => 'Editar registros',
            'eliminar' => 'Eliminar registros',
            'detalle' => 'Ver detalle',
        ];

        $this->crearPermisos($idModulo, $slug, $permisos, 'crud');
    }

    private function crearPermisos(int $idModulo, string $slug, array $permisos, string $tipo = 'crud'): void
    {
        foreach ($permisos as $accion => $descripcion) {
            $nombrePermiso = "{$slug}.{$accion}";

            DB::table('permissions')->insert([
                'id_modulo' => $idModulo,
                'name' => $nombrePermiso,
                'slug' => $nombrePermiso,
                'guard_name' => 'colaborador',
                'descripcion' => $descripcion,
                'tipo_permiso' => $tipo,
                'i_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("  ✓ {$nombrePermiso}");
        }
    }
}