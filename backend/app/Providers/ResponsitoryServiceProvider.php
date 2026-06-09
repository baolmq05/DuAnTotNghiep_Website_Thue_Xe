<?php

namespace App\Providers;

use App\Responsitories\Interfaces\UserResponsitoryInterface;
use App\Responsitories\User\UserResponsitory;
use Illuminate\Support\ServiceProvider;

class ResponsitoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserResponsitoryInterface::class, UserResponsitory::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
