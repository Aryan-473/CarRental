<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for roles
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manager', function (User $user) {
            return $user->role === 'manager' || $user->role === 'admin';
        });

        Gate::define('vendor', function (User $user) {
            return $user->role === 'vendor' || $user->role === 'admin';
        });

        Gate::define('user', function (User $user) {
            return in_array($user->role, ['user', 'manager', 'admin', 'vendor']);
        });

        // Additional gates for specific permissions
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-rentals', function (User $user) {
            return in_array($user->role, ['admin', 'vendor']);
        });

        Gate::define('view-reports', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });
    }
}
