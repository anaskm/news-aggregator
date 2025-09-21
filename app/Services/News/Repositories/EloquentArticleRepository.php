<?php

namespace App\Services\News\Repositories;

use App\Models\News\Article;
use App\Services\News\DTO\ArticleDTO;
use App\Services\Support\PaginatedResponse;
use App\Services\News\Contracts\ArticleRepository;

class EloquentArticleRepository implements ArticleRepository
{
    public function find(int $id): ?array
    {
        $article = Article::find($id);
        if (!$article) {
            return null;
        }

        return [
            'id' => $article->id,
            'title' => $article->title,
            'body' => $article->body,
            'url' => $article->url,
            'category' => $article->category,
            'source' => $article->provider_name,
            'published_at' => $article->published_at?->toISOString(),
        ];
    }

    public function save(ArticleDTO $dto, string $provider): void
    {
        $urlhash = md5($dto->url);

        Article::updateOrCreate(
            ['url_hash' => $urlhash],
            [
                'title' => $dto->title,
                'provider'  => $provider,
                'body'  => $dto->description,
                'url'   => $dto->url,
                'published_at'  => date('Y-m-d H:i:s', strtotime($dto->publishedAt)),
                'category'  => !empty($dto->category) ? $dto->category : 'General',
                'url_hash' => $urlhash,
            ]
        );
    }

    public function saveMany(iterable $dtos, string $provider): void
    {
        foreach ($dtos as $dto) {
            $this->save($dto, $provider);
        }
    }

    public function search(?string $searchQuery = '', ?string $provider = null, ?string $category = null, int $page = 1, int $perPage = 10): PaginatedResponse
    {
        $query = Article::query();

        if (!empty($searchQuery)) {
            $query->whereFullText(['title', 'body'], $searchQuery);
        }

        if (!empty($provider)) {
            $query->where('provider', $provider);
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        $paginator = $query->orderByDesc('published_at')->paginate($perPage, ['*'], 'page', $page);

        return new PaginatedResponse(
            $paginator->getCollection()->map(function (Article $article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'body' => $article->body,
                    'url' => $article->url,
                    'category' => $article->category,
                    'source' => $article->provider_name,
                    'published_at' => $article->published_at?->toISOString(),
                ];
            })->all(),
            [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ]
        );
    }
}
