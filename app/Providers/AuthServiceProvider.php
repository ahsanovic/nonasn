<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
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
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(15));

        // scopes
        Passport::tokensCan([
            'view-employees' => 'view employees based organization'
        ]);

        Gate::define('manage-data', function() {
            return (
                auth()->user()->id_skpd == 1 && auth()->user()->level == 'admin') || 
                (auth()->user()->id_skpd != 1 && auth()->user()->level == 'user'
            );
        });
    }
}
