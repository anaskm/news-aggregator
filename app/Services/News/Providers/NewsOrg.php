<?php
namespace App\Services\News\Providers;

use App\Services\News\DTO\Articles;
use Illuminate\Support\Facades\Http;
use App\Services\News\DTO\ArticleDTO;
use App\Services\News\Contracts\NewsService;
use App\Services\News\Transformers\ArticlesTransformer;

class NewsOrg implements NewsService
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
        $page = 1;
        $defaultFromDate = date('Y-m-d', strtotime('-1 day'));

        do {
            $response = Http::get("{$this->url}", [
                'q' => $this->options['q'] ?? 'us', // search query param is compulsory for this api
                'from' => $this->options['from'] ?? $defaultFromDate,
                'sortBy' => $this->options['sortBy'] ?? 'publishedAt',
                'apiKey'   => $this->apiKey,
                'page' => $page++,
            ]);
            $data = $response->json();

            $docs = array_merge($docs, $data['articles'] ?? []);

        } while (!empty($data['articles']) && $page < $this->pageLimit);

        return ArticlesTransformer::fromArray(
            $docs,
            fn ($article) => new ArticleDTO(
                $article['title'] ?? '',
                $article['description'] ?? '',
                $article['url'] ?? null,
                $article['publishedAt'] ?? null,
                $article['category'] ?? "",
            )
        );
    }
}