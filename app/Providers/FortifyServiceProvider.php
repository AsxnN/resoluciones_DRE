<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // ========================================
        // LOGIN UNIFICADO CON USERNAME
        // ========================================
        Fortify::authenticateUsing(function (Request $request) {
            // Validar que venga el username
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            // Buscar usuario por username
            $user = User::where('username', $request->username)
                ->where('i_active', true)
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'username' => __('Credenciales incorrectas.'),
                ]);
            }

            // Actualizar última sesión
            $user->update(['ultima_sesion' => now()]);

            // Registrar auditoría de login
            \App\Models\Auditoria::create([
                'tabla_afectada' => 'users',
                'id_registro' => $user->id,
                'accion' => 'login',
                'id_usuario' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'descripcion' => "Login {$user->tipo_acceso} exitoso",
            ]);

            return $user;
        });

        // Definir vista de login
        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}