<?php
// filepath: app/Observers/PersonaObserver.php

namespace App\Observers;

use App\Models\Persona;
use App\Models\User;
use App\Models\Colaborador as ColaboradorModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonaObserver
{
    /**
     * Handle the Persona "created" event.
     * Si la persona es tipo colaborador, crear usuario y colaborador automáticamente
     */
    public function created(Persona $persona): void
    {
        if ($persona->tipo_persona === 'colaborador' && !$persona->usuario) {
            DB::transaction(function () use ($persona) {
                // 1. Generar nombre completo
                $nombreCompleto = trim(implode(' ', [
                    $persona->nombres,
                    $persona->apellido_paterno,
                    $persona->apellido_materno
                ]));
                
                // 2. Crear Usuario con contraseña por defecto "Contraseña123"
                $usuario = User::withoutEvents(function () use ($persona, $nombreCompleto) {
                    return User::create([
                        'name' => $nombreCompleto,
                        'email' => $persona->correo ?? 'user' . $persona->id_persona . '@temp.com',
                        'password' => Hash::make('Contraseña123'),
                        'id_persona' => $persona->id_persona,
                        'i_active' => true,
                    ]);
                });
                
                // 3. Crear Colaborador vacío
                ColaboradorModel::create([
                    'id_colab_dis' => 'COL-' . str_pad($persona->id_persona, 6, '0', STR_PAD_LEFT),
                    'id_persona' => $persona->id_persona,
                    'id_usuario' => $usuario->id,
                    'id_cargos' => null,
                    'id_unidades' => null,
                    'id_direcciones' => null,
                    'id_dependencia' => null,
                    'id_area' => null,
                    'id_especialidad' => null,
                    'id_tipo_personal' => null,
                    'i_active' => false,
                ]);
            });
        }
    }

    /**
     * Handle the Persona "updating" event.
     * Sincronizar cambios de nombre y correo con usuario
     */
    public function updating(Persona $persona): void
    {
        // Solo sincronizar si cambió alguno de estos campos Y tiene usuario
        if ($persona->isDirty(['nombres', 'apellido_paterno', 'apellido_materno', 'correo'])) {
            $usuario = User::where('id_persona', $persona->id_persona)->first();
            
            if ($usuario) {
                $nombreCompleto = trim(implode(' ', [
                    $persona->nombres,
                    $persona->apellido_paterno,
                    $persona->apellido_materno
                ]));
                
                // Actualizar sin disparar eventos para evitar bucle
                User::withoutEvents(function () use ($usuario, $nombreCompleto, $persona) {
                    $usuario->update([
                        'name' => $nombreCompleto,
                        'email' => $persona->correo,
                    ]);
                });
            }
        }
    }
}