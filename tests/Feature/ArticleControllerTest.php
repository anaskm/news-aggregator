<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\News\Article;
use Illuminate\Support\Facades\Config;

class ArticleControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }
    use RefreshDatabase;

    public function test_index_paginated_articles_and_filters(): void
    {
        Config::set('newsfeeds.providers', [
            'news_org' => ['name' => 'News ORG'],
            'nytimes' => ['name' => 'New York Times'],
        ]);

        Article::factory()->create([
            'title' => 'Breaking: Laravel Testing',
            'body' => 'Laravel makes testing delightful',
            'provider' => 'nytimes',
            'category' => 'Tech',
            'published_at' => now()->subHour(),
        ]);

        Article::factory()->create([
            'title' => 'Sports update',
            'body' => 'Football finals',
            'provider' => 'news_org',
            'category' => 'Sports',
            'published_at' => now()->subHours(2),
        ]);

        $resp = $this->getJson('/api/v1/articles?source=nytimes&per_page=10');
        $resp->assertOk()
            ->assertJsonStructure([
                'data' => [
                    ['id','title','body','url','category','source','published_at']
                ],
                'meta' => ['current_page','per_page','total','last_page']
            ]);

        $json = $resp->json();
        $this->assertCount(1, $json['data']);
        $this->assertSame('New York Times', $json['data'][0]['source']);
    }

    public function test_index_invalid_provider(): void
    {
        Config::set('newsfeeds.providers', [ 'nytimes' => ['name' => 'New York Times'] ]);
        $this->getJson('/api/v1/articles?source=invalid')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['source']);
    }

    public function test_missing_article(): void
    {
        $this->getJson('/api/v1/articles/999999')->assertStatus(404);
    }

    public function test_show_fetch_article(): void
    {
        Config::set('newsfeeds.providers', [ 'nytimes' => ['name' => 'New York Times'] ]);
        $article = Article::factory()->create([
            'provider' => 'nytimes',
            'title' => 'Detail Page',
            'published_at' => now(),
        ]);

        $this->getJson('/api/v1/articles/'.$article->id)
            ->assertOk()
            ->assertJson([
                'id' => $article->id,
                'title' => 'Detail Page',
            ]);
    }
}
