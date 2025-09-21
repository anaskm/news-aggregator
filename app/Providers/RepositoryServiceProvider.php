<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\News\Repositories\EloquentArticleRepository;
use App\Services\News\Contracts\ArticleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleRepository::class, EloquentArticleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
