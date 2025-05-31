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
        $this->app->bind(
            \App\Services\Schedule\Interfaces\WorkingDaysValidatorInterface::class,
            \App\Services\Schedule\Validators\WorkingDaysValidator::class
        );

        $this->app->bind(
            \App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface::class,
            \App\Services\Schedule\Validators\WorkingHoursValidator::class
        );

        $this->app->bind(
            \App\Services\Schedule\Interfaces\ScheduleConflictValidatorInterface::class,
            \App\Services\Schedule\Validators\ScheduleConflictValidator::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\ScheduleRepositoryInterface::class,
            \App\Repositories\ScheduleRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
