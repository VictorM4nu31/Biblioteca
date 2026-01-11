<?php

namespace App\Providers;

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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Collection::class, \App\Policies\CollectionPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Rating::class, \App\Policies\RatingPolicy::class);

        // Permitir implícitamente que el 'admin' tenga todos los permisos
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
