<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\News\Providers\NewsApi;

class NewsApiTest extends TestCase
{
    public function test_get_articles(): void
    {
        Http::fakeSequence()
            ->push([
                'articles' => [
                    'results' => [
                        [
                            'title' => 'NewsAPI Title',
                            'body' => 'NewsAPI Body sample',
                            'url' => 'http://newsapi.test/a',
                            'dateTimePub' => '2025-03-03T00:00:00Z',
                            'dataType' => 'Business',
                        ],
                    ],
                ],
            ])
            ->push([
                'articles' => [
                    'results' => [],
                ],
            ]);

        $svc = new NewsApi('http://newsapi.test', 'key', 'uri_id', [], 5);
        $articles = $svc->getArticles();

        $this->assertSame(1, $articles->count());
        $first = $articles->all()[0];
        $this->assertSame('NewsAPI Title', $first->title);
        $this->assertSame('NewsAPI Body sample', $first->description);
        $this->assertSame('http://newsapi.test/a', $first->url);
        $this->assertSame('2025-03-03T00:00:00Z', $first->publishedAt);
        $this->assertSame('Business', $first->category);
    }
}
