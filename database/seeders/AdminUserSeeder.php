<?php
// filepath: database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // 1. Crear persona
            $persona = Persona::create([
                'nombre' => 'Super',
                'apellido_paterno' => 'Administrador',
                'apellido_materno' => 'Sistema',
                'tipo_documento' => 'DNI',
                'num_documento' => '00000000',
                'correo' => 'sgrResoluciones@resoluciones.gob.pe',
                'telefono' => '999999999',
                'tipo_persona' => 'colaborador',
                'i_active' => true,
            ]);

            // 2. Crear usuario admin
            $admin = User::create([
                'id_persona' => $persona->id_persona,
                'tipo_acceso' => 'admin',
                'name' => 'Super Administrador',
                'email' => 'admin@resoluciones.gob.pe',
                'email_alternativo' => 'admin.backup@resoluciones.gob.pe',
                'password' => Hash::make('Admin123!'), // ⚠️ CAMBIAR EN PRODUCCIÓN
                'i_active' => true,
                'email_verified_at' => now(),
            ]);

            // 3. Asignar todos los permisos de admin
            $permisosAdmin = \App\Models\Permiso::where('id_modulo', function($query) {
                $query->select('id_modulo')
                      ->from('modulos')
                      ->where('slug', 'gestion-privilegios')
                      ->limit(1);
            })->pluck('name');

            foreach ($permisosAdmin as $permiso) {
                $admin->givePermissionTo($permiso);
            }

            DB::commit();

            $this->command->info('✅ Usuario admin creado exitosamente');
            $this->command->warn('⚠️  Email: admin@resoluciones.gob.pe');
            $this->command->warn('⚠️  Password: Admin123!');
            $this->command->error('🔒 ¡CAMBIAR PASSWORD EN PRODUCCIÓN!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error al crear usuario admin: ' . $e->getMessage());
        }
    }
}