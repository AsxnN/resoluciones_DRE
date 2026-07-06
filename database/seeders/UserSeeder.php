<?php
// filepath: database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command->error('UserSeeder está deshabilitado en producción.');
            return;
        }

        // ========================================
        // 1. CREAR PERSONA ADMINISTRADOR
        // ========================================
        $personaAdmin = Persona::withoutEvents(function () {
            return Persona::create([
                'tipo_persona' => 'colaborador',
                'tipo_documento' => 'DNI',
                'num_documento' => '12345678',
                'nombres' => 'Administrador',
                'apellido_paterno' => 'Sistema',
                'apellido_materno' => 'Principal',
                'correo' => 'admin@resoluciones.gob.pe',
                'telefono' => '999888777',
                'datos_completos' => true,
                'obtenido_reniec' => true,
                'i_active' => true,
            ]);
        });

        // 2. CREAR USUARIO ADMINISTRADOR MANUALMENTE
        $admin = User::create([
            'id_persona' => $personaAdmin->id_persona,
            'name' => 'Administrador Sistema',
            'username' => 'admin',
            'email' => 'admin@resoluciones.gob.pe',
            'password' => Hash::make('Admin123456'),
            'tipo_acceso' => 'admin',
            'i_active' => true,
        ]);

        // ========================================
        // 3. CREAR PERSONA COLABORADOR DE PRUEBA
        // ========================================
        $personaColab = Persona::withoutEvents(function () {
            return Persona::create([
                'tipo_persona' => 'colaborador',
                'tipo_documento' => 'DNI',
                'num_documento' => '87654321',
                'nombres' => 'Juan Carlos',
                'apellido_paterno' => 'Pérez',
                'apellido_materno' => 'García',
                'correo' => 'colaborador@resoluciones.gob.pe',
                'telefono' => '999777666',
                'datos_completos' => true,
                'obtenido_reniec' => true,
                'i_active' => true,
            ]);
        });

        // 4. CREAR USUARIO COLABORADOR MANUALMENTE
        $colaborador = User::create([
            'id_persona' => $personaColab->id_persona,
            'name' => 'Juan Pérez',
            'username' => 'jperez',
            'email' => 'colaborador@resoluciones.gob.pe',
            'password' => Hash::make('Colab123456'),
            'tipo_acceso' => 'colaborador',
            'i_active' => true,
        ]);

        // Asignar TODOS los permisos al colaborador de prueba
        $colaborador->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // ========================================
        // 5. CREAR PERSONA CLIENTE DE PRUEBA
        // ========================================
        $personaCliente = Persona::create([
            'tipo_persona' => 'cliente',
            'tipo_documento' => 'DNI',
            'num_documento' => '11223344',
            'nombres' => 'María',
            'apellido_paterno' => 'López',
            'apellido_materno' => 'Rodríguez',
            'correo' => 'cliente@gmail.com',
            'telefono' => '999555444',
            'datos_completos' => true,
            'obtenido_reniec' => true,
            'i_active' => true,
        ]);

        // 6. CREAR CLIENTE
        $cliente = \App\Models\Cliente::create([
            'id_persona' => $personaCliente->id_persona,
            'i_active' => true,
        ]);

        // 7. CREAR USUARIO CLIENTE
        User::create([
            'id_persona' => $personaCliente->id_persona,
            'name' => 'María López',
            'username' => 'mlopez',
            'email' => 'cliente@gmail.com',
            'password' => Hash::make('Cliente123456'),
            'tipo_acceso' => 'cliente',
            'i_active' => true,
        ]);

        $this->command->info('✅ Usuarios creados:');
        $this->command->info('   👤 Admin: admin / Admin123456');
        $this->command->info('   👔 Colaborador: jperez / Colab123456');
        $this->command->info('   👨‍💼 Cliente: mlopez / Cliente123456');
    }
}