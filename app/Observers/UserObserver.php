<?php
// filepath: app/Observers/UserObserver.php

namespace App\Observers;

use App\Models\User;
use App\Models\Persona;
use App\Models\Colaborador as ColaboradorModel;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Si se crea un usuario SIN persona asociada, crear persona y colaborador
     */
    public function created(User $user): void
    {
        // Solo si NO tiene id_persona asignado
        if (!$user->id_persona) {
            DB::transaction(function () use ($user) {
                // Extraer partes del nombre
                $nombreCompleto = $user->name;
                $partes = explode(' ', $nombreCompleto, 3);
                
                $nombres = $partes[0] ?? '';
                $apellidoPaterno = $partes[1] ?? '';
                $apellidoMaterno = $partes[2] ?? null;
                
                // 1. Crear Persona sin disparar eventos
                $persona = Persona::withoutEvents(function () use ($nombres, $apellidoPaterno, $apellidoMaterno, $user) {
                    return Persona::create([
                        'tipo_persona' => 'colaborador',
                        'tipo_documento' => 'DNI',
                        'num_documento' => 'TEMP-' . time(),
                        'nombres' => $nombres,
                        'apellido_paterno' => $apellidoPaterno,
                        'apellido_materno' => $apellidoMaterno,
                        'correo' => $user->email,
                        'telefono' => null,
                        'whatsapp' => null,
                        'direccion' => null,
                        'datos_completos' => false,
                        'i_active' => true,
                    ]);
                });
                
                // 2. Actualizar usuario con id_persona
                $user->update(['id_persona' => $persona->id_persona]);
                
                // 3. Crear Colaborador vacío
                ColaboradorModel::create([
                    'id_colab_dis' => 'COL-' . str_pad($persona->id_persona, 6, '0', STR_PAD_LEFT),
                    'id_persona' => $persona->id_persona,
                    'id_usuario' => $user->id,
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
     * Handle the User "updating" event.
     * Sincronizar cambios con persona
     */
    public function updating(User $user): void
    {
        if ($user->isDirty(['name', 'email']) && $user->id_persona) {
            $persona = Persona::find($user->id_persona);
            
            if ($persona) {
                $updates = [];
                
                // Sincronizar email
                if ($user->isDirty('email')) {
                    $updates['correo'] = $user->email;
                }
                
                // Sincronizar name
                if ($user->isDirty('name')) {
                    $nombreCompleto = $user->name;
                    $partes = explode(' ', $nombreCompleto, 3);
                    
                    $updates['nombres'] = $partes[0] ?? '';
                    $updates['apellido_paterno'] = $partes[1] ?? '';
                    $updates['apellido_materno'] = $partes[2] ?? null;
                }
                
                // Actualizar sin disparar eventos para evitar bucle
                if (!empty($updates)) {
                    Persona::withoutEvents(function () use ($persona, $updates) {
                        $persona->update($updates);
                    });
                }
            }
        }
    }
}