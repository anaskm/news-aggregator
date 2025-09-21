<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\News\Repositories\EloquentArticleRepository;
use App\Models\News\Article;
use Illuminate\Support\Facades\Config;

class EloquentArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_paginated_response(): void
    {
        Config::set('newsfeeds.providers', [ 'nytimes' => ['name' => 'New York Times'] ]);
        Article::factory()->count(2)->create(['provider' => 'nytimes']);

        $repo = new EloquentArticleRepository();
        $res = $repo->search('', 'nytimes', null, 1, 10);

        $this->assertIsArray($res->toArray()['data']);
        $this->assertSame(2, $res->meta['total']);
        $this->assertArrayHasKey('source', $res->data[0]);
    }

    public function test_record_not_found(): void
    {
        $repo = new EloquentArticleRepository();
        $this->assertNull($repo->find(12345));
    }
}
