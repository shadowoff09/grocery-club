<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Podes definir mais gates aqui:
        Gate::define('is-board', fn(User $user) => $user->type === 'board');
        Gate::define('is-employee', fn(User $user) => $user->type === 'employee');
        Gate::define('is-member', fn(User $user) => $user->type === 'member');
    }
}
