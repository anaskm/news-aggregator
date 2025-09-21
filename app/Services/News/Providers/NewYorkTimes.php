<?php
namespace App\Services\News\Providers;

use App\Services\News\DTO\Articles;
use Illuminate\Support\Facades\Http;
use App\Services\News\DTO\ArticleDTO;
use App\Services\News\Contracts\NewsService;
use App\Services\News\Transformers\ArticlesTransformer;

class NewYorkTimes implements NewsService
{
     public function __construct(
        private string $url, 
        private string $apiKey, 
        private array $options = [],
        private int $pageLimit = 10)
    {
    }

    public function getArticles(): Articles
    {
        $docs = [];
        $page = 0;
        
        $defaultFromDate = date('Ymd', strtotime('-1 day'));

        do {
            $response = Http::get("{$this->url}", [
                'api-key'   => $this->apiKey,
                'begin_date' => $this->options['from'] ?? $defaultFromDate,
                'page' => $page++,
            ]);
            $data = $response->json();
            $docs = array_merge($docs, $data['response']['docs'] ?? []);

        } while (!empty($data['response']['docs']) && $page < $this->pageLimit);

        return ArticlesTransformer::fromArray(
            $docs,
            fn ($article) => new ArticleDTO(
                $article['headline']['main'] ?? '',
                $article['snippet'] ?? '',
                $article['web_url'] ?? null,
                $article['pub_date'] ?? null,
                $article['subsection_name'] ?? "",
            )
        );
    }
}