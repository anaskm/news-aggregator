<?php

namespace App\Services\News\DTO;

use ArrayIterator;
use IteratorAggregate;
use InvalidArgumentException;
use App\Services\News\DTO\ArticleDTO;

class Articles implements IteratorAggregate
{
    /** @var ArticleDTO[] */
    private array $articles;

    /**
     * @param ArticleDTO[] $articles
     */
    public function __construct(array $articles = [])
    {
        foreach ($articles as $article) {
            if (!$article instanceof ArticleDTO) {
                throw new InvalidArgumentException("All items must be instances of " . ArticleDTO::class);
            }
        }

        $this->articles = $articles;
    }

    /**
     * @return ArticleDTO[]
     */
    public function all(): array
    {
        return $this->articles;
    }

    public function add(ArticleDTO $article): void
    {
        $this->articles[] = $article;
    }

    public function count(): int
    {
        return count($this->articles);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->articles);
    }
}
