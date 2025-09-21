<?php
namespace App\Services\News\Contracts;

use App\Services\News\DTO\Articles;

interface NewsService
{
    public function getArticles(): Articles;
}