<?php

namespace App\Services\News\Contracts;

use App\Services\News\DTO\ArticleDTO;
use App\Services\Support\PaginatedResponse;

interface ArticleRepository
{
    /**
     * Persist a single article. Storage-agnostic.
     */
    public function save(ArticleDTO $dto, string $provider): void;

    /**
     * @param iterable<ArticleDTO> $dtos
     */
    public function saveMany(iterable $dtos, string $provider): void;

    /**
     * Find a single article by id.
     *
     * @return array<string,mixed>|null
     */
    public function find(int $id): ?array;

    /**
     * Search stored articles without leaking ORM types.
     */
    public function search(?string $searchQuery = '', ?string $provider = null, ?string $category = null, int $page = 1, int $perPage = 10): PaginatedResponse;
}
