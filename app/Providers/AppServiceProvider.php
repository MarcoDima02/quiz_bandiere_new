<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\CountryRepositoryInterface;
use App\Contracts\QuizScoreRepositoryInterface;
use App\Contracts\QuizServiceInterface;
use App\Repositories\CountryRepository;
use App\Repositories\QuizScoreRepository;
use App\Services\QuizService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces to Implementations
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(QuizScoreRepositoryInterface::class, QuizScoreRepository::class);
        
        // Bind Service Interfaces to Implementations
        $this->app->bind(QuizServiceInterface::class, QuizService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
