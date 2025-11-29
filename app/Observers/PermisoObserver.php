<?php
// filepath: app/Observers/PermisoObserver.php

namespace App\Observers;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PermisoObserver
{
    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        $this->limpiarCache();
        Log::info('Permiso creado', ['permiso' => $permission->name]);
    }

    /**
     * Handle the Permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        $this->limpiarCache();
        Log::info('Permiso actualizado', ['permiso' => $permission->name]);
    }

    /**
     * Handle the Permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {
        $this->limpiarCache();
        Log::info('Permiso eliminado', ['permiso' => $permission->name]);
    }

    /**
     * Limpiar caché de permisos
     */
    private function limpiarCache(): void
    {
        Cache::forget('spatie.permission.cache');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}