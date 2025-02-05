<?php

namespace App\Providers;
use App\Policies\UserPolicy;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        RateLimiter::for('phone-login', function ($request) {
            return Limit::perMinute(3)->by($request->ip()); // Limit to 3 attempts per minute per IP
        });
        
    }
}
