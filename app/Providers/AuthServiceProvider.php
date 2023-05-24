<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Passport::routes(); // Add this line to enable Laravel Passport routes

        Passport::tokensExpireIn(now()->addDays(7)); // Set token expiration time (optional)
        Passport::refreshTokensExpireIn(now()->addDays(30)); // Set refresh token expiration time (optional)

        // Additional configuration options for Laravel Passport can be added here
    }
}
