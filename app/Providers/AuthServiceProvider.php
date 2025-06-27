<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::define('update-book', function (User $user) {
            return $user->isAdmin() || $user->isLibrarian();
        });

        Gate::define('create-book', function (User $user) {
            return $user->isAdmin() || $user->isLibrarian();
        });

        Gate::define('delete-book', function (User $user) {
            return $user->isAdmin() || $user->isLibrarian();
        });

        Gate::define('manage-loans', function (User $user) {
            return $user->isAdmin() || $user->isLibrarian();
        });
    }
}
