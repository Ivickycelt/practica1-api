<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
public function boot(): void
    {
        // Gate para Crear: Administradores y Editores autorizados
        Gate::define('create-producto', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });

        // Gate para Editar: Administradores y Editores autorizados
        Gate::define('update-producto', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });

        // Gate para Eliminar: EXCLUSIVO para el Administrador
        Gate::define('delete-producto', function (User $user) {
            return $user->esAdmin(); // Llama al helper de tu modelo User
        });
    }
}