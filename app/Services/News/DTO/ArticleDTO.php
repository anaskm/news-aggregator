<?php

namespace App\Services\News\DTO;

class ArticleDTO
{
    public function __construct(
        public string $title, 
        public string $description, 
        public string $url, 
        public string $publishedAt, 
        public string $category)
    {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'published_at' => $this->publishedAt,
            'category' => $this->category,
        ];
    }
}
