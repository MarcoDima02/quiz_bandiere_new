<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\CountryRepositoryInterface;
use App\Contracts\QuizScoreRepositoryInterface;
use App\Contracts\QuizServiceInterface;
use App\Contracts\MemoryGameServiceInterface;
use App\Contracts\BonusGameServiceInterface;
use App\Repositories\CountryRepository;
use App\Repositories\QuizScoreRepository;
use App\Services\QuizService;
use App\Services\MemoryGameService;
use App\Services\BonusGameService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
                // Binding dei Repository
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(QuizScoreRepositoryInterface::class, QuizScoreRepository::class);
        
        // Binding dei Service
        $this->app->bind(QuizServiceInterface::class, QuizService::class);
        $this->app->bind(MemoryGameServiceInterface::class, MemoryGameService::class);
        $this->app->bind(BonusGameServiceInterface::class, BonusGameService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
