<?php
namespace App\Services\News\Providers;

use App\Services\News\DTO\Articles;
use Illuminate\Support\Facades\Http;
use App\Services\News\DTO\ArticleDTO;
use App\Services\News\Contracts\NewsService;
use App\Services\News\Transformers\ArticlesTransformer;

class NewsApi implements NewsService
{
    public function __construct(
        private string $url, 
        private string $apiKey, 
        private string $uriId, 
        private array $options = [],
        private int $pageLimit = 10)
    {
    }

    public function getArticles(): Articles
    {
        $docs = [];
        $page = 1;

        do {
            $response = Http::get("{$this->url}", [
                'uri' => $this->uriId,
                'articlesPage' => $page++,
                'articlesCount' => $this->options['articlesCount'] ?? 10,
                'resultType' => $this->options['resultType'] ?? 'articles',
                'articlesSortBy' => $this->options['articlesSortBy'] ?? "date",
                'apiKey'   => $this->apiKey,
                'articleBodyLength' => $this->options['articleBodyLength'] ?? -1,
                'forceMaxDataTimeWindow' => $this->options['forceMaxDataTimeWindow'] ?? 2,
            ]);
            $data = $response->json();
            $docs = array_merge($docs, $data['articles']['results'] ?? []);

        } while (!empty($data['articles']['results']) && $page < $this->pageLimit);

        return ArticlesTransformer::fromArray(
            $docs,
            fn ($article) => new ArticleDTO(
                $article['title'] ?? null,
                $article['body'] ?? null,
                $article['url'] ?? null,
                $article['dateTimePub'] ?? null,
                $article['dataType'] ?? "",
            )
        );
    }
}