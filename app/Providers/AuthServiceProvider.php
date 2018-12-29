<?php

namespace App\Providers;

use Auth;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use App\Extentions\EloquentUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
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

        Passport::tokensExpireIn(now()->addDays(15));

        Passport::refreshTokensExpireIn(now()->addDays(30));

        // 通过自定义的 EloquentUserProvider 覆盖系统默认的
        Auth::provider('eloquent', function ($app, $config) {
            return new EloquentUserProvider($app->make('hash'), $config['model']);
        });
    }
}
