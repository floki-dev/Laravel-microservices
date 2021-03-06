<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensCan([
            'admin' => 'Admin access',
            'influencer' => 'Influencer access',
        ]);

        Gate::define(
            'view',
            static function (User $user, $model) {
                return $user->hasAccess("view_{$model}") || $user->hasAccess("edit_{$model}");
            }
        );

        Gate::define(
            'edit',
            static function (User $user, $model) {
                return $user->hasAccess("edit_{$model}");
            }
        );
    }
}
