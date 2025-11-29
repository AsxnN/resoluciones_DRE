<?php
// filepath: app/Observers/UserObserver.php

namespace App\Observers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->registrarAuditoria($user, 'crear', "Usuario creado: {$user->email}");

        Log::info('Usuario creado', [
            'id' => $user->id,
            'email' => $user->email,
            'tipo_acceso' => $user->tipo_acceso,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $cambios = $user->getDirty();

        if (empty($cambios)) {
            return;
        }

        // Ocultar password en logs
        if (isset($cambios['password'])) {
            $cambios['password'] = '***';
        }

        $this->registrarAuditoria($user, 'actualizar', "Usuario actualizado: {$user->email}", $cambios);

        Log::info('Usuario actualizado', [
            'id' => $user->id,
            'cambios' => array_keys($cambios),
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->registrarAuditoria($user, 'eliminar', "Usuario eliminado: {$user->email}");

        Log::warning('Usuario eliminado', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    /**
     * Registrar en auditoría
     */
    private function registrarAuditoria(User $user, string $accion, string $descripcion, array $cambios = []): void
    {
        if (!Auth::check()) {
            return;
        }

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => $accion,
            'tabla_afectada' => 'users',
            'id_registro' => $user->id,
            'descripcion' => $descripcion,
            'datos_anteriores' => !empty($cambios) ? json_encode($user->getOriginal()) : null,
            'datos_nuevos' => !empty($cambios) ? json_encode($cambios) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}