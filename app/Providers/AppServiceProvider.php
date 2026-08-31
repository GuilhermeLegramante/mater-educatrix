<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Define o Gate 'admin' comparando tanto o Enum quanto o valor em string
        Gate::define('admin', function (User $user) {
            // Se o cast no Model estiver ativo:
            if ($user->role instanceof UserRole) {
                return $user->role === UserRole::ADMIN;
            }

            // Fallback caso venha como string do banco
            return strtolower((string) $user->role) === 'admin';
        });

        // Define acesso para professores ou admins
        Gate::define('teacher', function (User $user) {
            return in_array($user->role, [UserRole::ADMIN, UserRole::TEACHER]);
        });
    }
}
