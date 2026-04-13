<?php

namespace App\Providers;

use App\Repositories\UltrasonicTestRepository;
use App\Repositories\UltrasonicTestRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UltrasonicTestRepositoryInterface::class, UltrasonicTestRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
