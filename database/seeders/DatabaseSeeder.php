<?php
// filepath: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Iniciando seeders...');

        // 1. Módulos (base para permisos)
        $this->command->info('📦 Creando módulos...');
        $this->call(ModuloSeeder::class);

        // 2. Permisos (vinculados a módulos)
        $this->command->info('🔐 Creando permisos...');
        $this->call(PermisoSeeder::class);

        // 3. Catálogos del sistema
        $this->command->info('📚 Creando catálogos...');
        $this->call([
            EstadoSeeder::class,
            EstadoFirmaSeeder::class,
            TipoResolucionSeeder::class,
            CatalogosSeeder::class,
        ]);

        // 4. Usuario Admin inicial
        $this->command->info('👤 Creando usuario admin...');
        $this->call(UserSeeder::class);

        $this->command->info('✅ Seeders completados exitosamente!');
    }
}