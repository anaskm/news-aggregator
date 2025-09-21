<?php

namespace App\Console\Commands\News;

use Illuminate\Console\Command;
use App\Services\News\NewsAggregatorService;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news articles from providers';

    /**
     * Execute the console command.
     */
    public function handle(NewsAggregatorService $newsAggregator)
    {
        $this->info('Fetching latest articles from providers. Please wait...');
        $newsAggregator->fetchAndStore();
        $this->info('Articles stored successfully.');
    }
}
