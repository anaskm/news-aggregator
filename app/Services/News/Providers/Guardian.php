<?php
namespace App\Services\News\Providers;

use App\Services\News\DTO\Articles;
use Illuminate\Support\Facades\Http;
use App\Services\News\DTO\ArticleDTO;
use App\Services\News\Contracts\NewsService;
use App\Services\News\Transformers\ArticlesTransformer;
use Illuminate\Contracts\Http\Client\Factory as HttpFactory;

class Guardian implements NewsService
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
                'api-key'   => $this->apiKey,
                'from-date' => $this->options['from-date'] ?? $defaultFromDate,
                'show-fields' => $this->options['show-fields'] ?? 'webTitle,bodyText,webUrl,webPublicationDate,sectionName',
                'page' => $page++,
            ]);
            $data = $response->json();

            $docs = array_merge($docs, $data['response']['results'] ?? []);

        } while (!empty($data['response']['results']) && $page < $this->pageLimit);

        return ArticlesTransformer::fromArray(
            $docs,
            fn ($article) => new ArticleDTO(
                $article['webTitle'] ?? null,
                $article["fields"]['bodyText'] ?? "",
                $article['webUrl'] ?? null,
                $article['webPublicationDate'] ?? null,
                $article['sectionName'] ?? "",
            )
        );
    }
}