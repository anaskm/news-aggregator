<?php

namespace App\Services\News;

use App\Jobs\StoreArticlesFromProvider;
use App\Services\News\NewsSourceProviderResolver;

class NewsAggregatorService
{   
    public function __construct(private NewsSourceProviderResolver $providerResolver)
    {
        
    }

    public function fetchAndStore()
    {
        $providers = $this->providerResolver->getAllProviders();

        foreach ($providers as $provider) {
            StoreArticlesFromProvider::dispatch($provider);
        }
    }
}
