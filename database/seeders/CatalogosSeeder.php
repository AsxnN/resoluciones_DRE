<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. CARGOS (con codigo_cargo)
        DB::table('cargo')->insert([
            ['codigo_cargo' => 'DIR-REG', 'nombre_cargo' => 'Director Regional', 'descripcion' => 'Director de la Dirección Regional de Educación', 'i_active' => true, 'id_usuario' => 1],
            ['codigo_cargo' => 'SUBDIR-PED', 'nombre_cargo' => 'Subdirector de Gestión Pedagógica', 'descripcion' => 'Responsable de la gestión pedagógica', 'i_active' => true, 'id_usuario' => 1],
            ['codigo_cargo' => 'SUBDIR-INS', 'nombre_cargo' => 'Subdirector de Gestión Institucional', 'descripcion' => 'Responsable de la gestión institucional', 'i_active' => true, 'id_usuario' => 1],
            ['codigo_cargo' => 'JEFE-AREA', 'nombre_cargo' => 'Jefe de Área', 'descripcion' => 'Jefe de área administrativa', 'i_active' => true, 'id_usuario' => 1],
            ['codigo_cargo' => 'ESP-EDU', 'nombre_cargo' => 'Especialista en Educación', 'descripcion' => 'Especialista en temas educativos', 'i_active' => true, 'id_usuario' => 1],
            ['codigo_cargo' => 'COORD', 'nombre_cargo' => 'Coordinador', 'descripcion' => 'Coordinador de programas educativos', 'i_active' => true, 'id_usuario' => 1],
            ['codigo_cargo' => 'ASIST-ADM', 'nombre_cargo' => 'Asistente Administrativo', 'descripcion' => 'Asistente en labores administrativas', 'i_active' => true, 'id_usuario' => 1],
            ['codigo_cargo' => 'SECRET', 'nombre_cargo' => 'Secretaria', 'descripcion' => 'Secretaria de dirección', 'i_active' => true, 'id_usuario' => 1],
            ['codigo_cargo' => 'TEC-ADM', 'nombre_cargo' => 'Técnico Administrativo', 'descripcion' => 'Técnico en procesos administrativos', 'i_active' => true, 'id_usuario' => 1],
            ['codigo_cargo' => 'ANALISTA', 'nombre_cargo' => 'Analista', 'descripcion' => 'Analista de procesos', 'i_active' => true, 'id_usuario' => 1],
        ]);

        // 2. UNIDADES (sin descripcion ni codigo)
        DB::table('unidad')->insert([
            ['nombre_unidad' => 'Unidad de Gestión Pedagógica', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_unidad' => 'Unidad de Gestión Institucional', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_unidad' => 'Unidad de Administración', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_unidad' => 'Unidad de Recursos Humanos', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_unidad' => 'Unidad de Planificación', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_unidad' => 'Unidad de Logística', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_unidad' => 'Unidad de Tecnología', 'i_active' => true, 'id_usuario' => 1],
        ]);

        // 3. DIRECCIONES (sin descripcion ni codigo)
        DB::table('direccion')->insert([
            ['nombre_direcciones' => 'Dirección Regional de Educación', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_direcciones' => 'Dirección de Gestión Pedagógica', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_direcciones' => 'Dirección de Gestión Institucional', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_direcciones' => 'Dirección de Administración', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_direcciones' => 'Dirección de Planificación', 'i_active' => true, 'id_usuario' => 1],
        ]);

        // 4. DEPENDENCIAS (con cod_dependencia, sin descripcion)
        DB::table('dependencia')->insert([
            ['cod_dependencia' => 'ORG-DIR', 'nombre_dependencia' => 'Órgano de Dirección', 'i_active' => true, 'id_usuario' => 1],
            ['cod_dependencia' => 'ORG-LIN', 'nombre_dependencia' => 'Órgano de Línea', 'i_active' => true, 'id_usuario' => 1],
            ['cod_dependencia' => 'ORG-APO', 'nombre_dependencia' => 'Órgano de Apoyo', 'i_active' => true, 'id_usuario' => 1],
            ['cod_dependencia' => 'ORG-ASE', 'nombre_dependencia' => 'Órgano de Asesoramiento', 'i_active' => true, 'id_usuario' => 1],
            ['cod_dependencia' => 'ORG-CON', 'nombre_dependencia' => 'Órgano de Control', 'i_active' => true, 'id_usuario' => 1],
        ]);

        // 5. ÁREAS (con descripcion)
        DB::table('area')->insert([
            ['nombre_area' => 'Área de Educación Inicial', 'descripcion' => 'Educación inicial y preescolar', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Educación Primaria', 'descripcion' => 'Educación primaria', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Educación Secundaria', 'descripcion' => 'Educación secundaria', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Educación Básica Alternativa', 'descripcion' => 'EBA', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Educación Especial', 'descripcion' => 'Educación especial e inclusiva', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Educación Superior', 'descripcion' => 'Educación superior no universitaria', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Recursos Humanos', 'descripcion' => 'Gestión de personal', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Finanzas', 'descripcion' => 'Gestión financiera', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Logística', 'descripcion' => 'Gestión de bienes y servicios', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Tecnología e Informática', 'descripcion' => 'Sistemas y tecnología', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área Legal', 'descripcion' => 'Asesoría legal', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_area' => 'Área de Planificación', 'descripcion' => 'Planificación estratégica', 'i_active' => true, 'id_usuario' => 1],
        ]);

        // 6. ESPECIALIDADES (sin descripcion ni codigo)
        DB::table('especialidad')->insert([
            ['nombre_especialidad' => 'Educación Inicial', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Educación Primaria', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Educación Secundaria - Matemática', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Educación Secundaria - Comunicación', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Educación Secundaria - Ciencias Sociales', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Educación Secundaria - Ciencia y Tecnología', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Educación Física', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Educación para el Trabajo', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Educación Especial', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Administración Educativa', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Gestión Pedagógica', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_especialidad' => 'Tecnología Educativa', 'i_active' => true, 'id_usuario' => 1],
        ]);

        // 7. TIPOS DE PERSONAL (sin descripcion, codigo ni id_usuario)
        DB::table('tipo_personal')->insert([
            ['nombre_tipo_personal' => 'Funcionario', 'i_active' => true],
            ['nombre_tipo_personal' => 'Directivo', 'i_active' => true],
            ['nombre_tipo_personal' => 'Especialista', 'i_active' => true],
            ['nombre_tipo_personal' => 'Profesional', 'i_active' => true],
            ['nombre_tipo_personal' => 'Técnico', 'i_active' => true],
            ['nombre_tipo_personal' => 'Auxiliar', 'i_active' => true],
            ['nombre_tipo_personal' => 'Contratado CAS', 'i_active' => true],
            ['nombre_tipo_personal' => 'Nombrado', 'i_active' => true],
            ['nombre_tipo_personal' => 'Destacado', 'i_active' => true],
        ]);

        // 8. ROLES ORGANIZACIONALES (con descripcion)
        DB::table('roles_organizacionales')->insert([
            ['nombre_rol' => 'Director Regional', 'descripcion' => 'Máxima autoridad de la DRE', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Subdirector', 'descripcion' => 'Subdirector de gestión', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Jefe de Área', 'descripcion' => 'Responsable de área', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Jefe de Unidad', 'descripcion' => 'Responsable de unidad', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Coordinador General', 'descripcion' => 'Coordinador de programas', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Coordinador de Área', 'descripcion' => 'Coordinador de área específica', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Especialista Senior', 'descripcion' => 'Especialista con experiencia avanzada', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Especialista', 'descripcion' => 'Especialista en temas educativos', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Analista', 'descripcion' => 'Analista de procesos', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Asistente', 'descripcion' => 'Asistente administrativo', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Secretaria de Dirección', 'descripcion' => 'Secretaria de alta dirección', 'i_active' => true, 'id_usuario' => 1],
            ['nombre_rol' => 'Soporte Técnico', 'descripcion' => 'Personal de soporte', 'i_active' => true, 'id_usuario' => 1],
        ]);

        $this->command->info('✅ Catálogos poblados exitosamente:');
        $this->command->info('   - 10 Cargos (con código)');
        $this->command->info('   - 7 Unidades');
        $this->command->info('   - 5 Direcciones');
        $this->command->info('   - 5 Dependencias (con código)');
        $this->command->info('   - 12 Áreas (con descripción)');
        $this->command->info('   - 12 Especialidades');
        $this->command->info('   - 9 Tipos de Personal');
        $this->command->info('   - 12 Roles Organizacionales (con descripción)');
    }
}