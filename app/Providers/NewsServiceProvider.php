<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\News\NewsSourceProviderResolver;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsSourceProviderResolver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerNewsOrgProvider();
        $this->registerNyTimesProvider();
        $this->registerGuardianProvider();
        $this->registerNewsApiProvider();
    }

    private function registerNewsOrgProvider()
    {
        $this->app->when(\App\Services\News\Providers\NewsOrg::class)
            ->needs('$url')
            ->give(config('newsfeeds.providers.news_org.base_url'));

        $this->app->when(\App\Services\News\Providers\NewsOrg::class)
            ->needs('$apiKey')
            ->give(config('newsfeeds.providers.news_org.api_key'));

        $this->app->make(NewsSourceProviderResolver::class)->add('news_org', $this->app->make(\App\Services\News\Providers\NewsOrg::class));
    }

    private function registerNyTimesProvider()
    {
        $this->app->when(\App\Services\News\Providers\NewYorkTimes::class)
            ->needs('$url')
            ->give(config('newsfeeds.providers.nytimes.base_url'));

        $this->app->when(\App\Services\News\Providers\NewYorkTimes::class)
            ->needs('$apiKey')
            ->give(config('newsfeeds.providers.nytimes.api_key'));

        $this->app->make(NewsSourceProviderResolver::class)->add('nytimes', $this->app->make(\App\Services\News\Providers\NewYorkTimes::class));
    }

    private function registerGuardianProvider()
    {
        $this->app->when(\App\Services\News\Providers\Guardian::class)
            ->needs('$url')
            ->give(config('newsfeeds.providers.guardian.base_url'));

        $this->app->when(\App\Services\News\Providers\Guardian::class)
            ->needs('$apiKey')
            ->give(config('newsfeeds.providers.guardian.api_key'));
            
        $this->app->make(NewsSourceProviderResolver::class)->add('guardian', $this->app->make(\App\Services\News\Providers\Guardian::class));
    }

    private function registerNewsApiProvider()
    {
        $this->app->when(\App\Services\News\Providers\NewsApi::class)
            ->needs('$url')
            ->give(config('newsfeeds.providers.news_ai.base_url'));

        $this->app->when(\App\Services\News\Providers\NewsApi::class)
            ->needs('$apiKey')
            ->give(config('newsfeeds.providers.news_ai.api_key'));

        $this->app->when(\App\Services\News\Providers\NewsApi::class)
            ->needs('$uriId')
            ->give(config('newsfeeds.providers.news_ai.url_id'));

        $this->app->make(NewsSourceProviderResolver::class)->add('news_ai', $this->app->make(\App\Services\News\Providers\NewsApi::class));
    }
}
