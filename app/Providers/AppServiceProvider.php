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
        \App\Models\TeacherProfile::observe(\App\Observers\TeacherProfileObserver::class);
        \App\Models\Organization::observe(\App\Observers\OrganizationObserver::class);
        \App\Models\Facilitator::observe(\App\Observers\FacilitatorObserver::class);
    }
}
