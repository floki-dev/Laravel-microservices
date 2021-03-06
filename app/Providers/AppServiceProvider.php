<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Passport::routes();

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
