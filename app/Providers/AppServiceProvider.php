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
        // Define que apenas administradores passam na porta 'admin'
        Gate::define('admin', function (User $user) {
            return $user->role === UserRole::ADMIN;
        });

        // Define acesso para professores ou admins
        Gate::define('teacher', function (User $user) {
            return in_array($user->role, [UserRole::ADMIN, UserRole::TEACHER]);
        });
    }
}
