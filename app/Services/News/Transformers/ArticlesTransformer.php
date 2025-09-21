<?php

namespace App\Services\News\Transformers;

use App\Services\News\DTO\Articles;

class ArticlesTransformer
{
    /**
     * @param array $items Raw provider response items
     * @param callable $mapFn function(array $raw): ArticleDTO
     */
    public static function fromArray(array $items, callable $mapFn): Articles
    {
        $articles = array_map($mapFn, $items);
        return new Articles($articles);
    }
}