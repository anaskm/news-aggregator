<?php
namespace App\Services\News;

use App\Services\News\Contracts\NewsService;

class NewsSourceProviderResolver
{
    private array $services;
    
    public function add(string $name, NewsService $service): void
    {
        $this->services[$name] = $service;
    }

    public function get(string $name): NewsService
    {
        if (!isset($this->services[$name])) {
            throw new \InvalidArgumentException("Service provider '{$name}' not found.");
        }
        
        return $this->services[$name];
    }

    public function getAllProviders(): array
    {
        return array_keys($this->services);
    }
}