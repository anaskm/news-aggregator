<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;
use App\Services\News\NewsSourceProviderResolver;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\News\Contracts\ArticleRepository;

class StoreArticlesFromProvider implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $providerName)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NewsSourceProviderResolver $providerResolver, ArticleRepository $articleRepository): void
    {
        $articleService = $providerResolver->get($this->providerName);
        $articles = $articleService->getArticles();

        if ($articles->count()) {
            $articleRepository->saveMany($articles, $this->providerName);
        }
    }
}
