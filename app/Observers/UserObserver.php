<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Crea automáticamente la persona cuando se registra un usuario
     */
    public function created(User $user): void
    {
        // Solo crear persona si no tiene una asignada
        if (!$user->id_persona) {
            DB::transaction(function () use ($user) {
                // Extraer nombres del name (Nombre Apellido_Paterno Apellido_Materno)
                $nombreCompleto = $user->name;
                $partes = explode(' ', $nombreCompleto);
                
                $nombres = $partes[0] ?? '';
                $apellidoPaterno = $partes[1] ?? '';
                $apellidoMaterno = isset($partes[2]) ? implode(' ', array_slice($partes, 2)) : null;
                
                // Crear persona
                $persona = Persona::create([
                    'tipo_persona' => 'colaborador', // Por defecto es colaborador
                    'tipo_documento' => 'DNI',
                    'num_documento' => '', // Se completará después en el perfil
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
                
                // Actualizar el user con el id_persona
                $user->update(['id_persona' => $persona->id_persona]);
            });
        }
    }

    /**
     * Handle the User "updating" event.
     * Sincroniza los cambios de user a persona
     */
    public function updating(User $user): void
    {
        if ($user->persona && $user->isDirty(['name', 'email'])) {
            // Sincronizar name y email a persona
            $updates = [];
            
            if ($user->isDirty('email')) {
                $updates['correo'] = $user->email;
            }
            
            if ($user->isDirty('name')) {
                // Parsear el nombre completo
                $partes = explode(' ', $user->name);
                $updates['nombres'] = $partes[0] ?? '';
                $updates['apellido_paterno'] = $partes[1] ?? '';
                $updates['apellido_materno'] = isset($partes[2]) ? implode(' ', array_slice($partes, 2)) : null;
            }
            
            if (!empty($updates)) {
                $user->persona->update($updates);
            }
        }
    }
}