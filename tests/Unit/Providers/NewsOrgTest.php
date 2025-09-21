<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\News\Providers\NewsOrg;

class NewsOrgTest extends TestCase
{
    public function test_get_articles(): void
    {
        Http::fakeSequence()
            ->push([
                'articles' => [
                    [
                        'title' => 'News Org Title',
                        'description' => 'News Org sample description',
                        'url' => 'http://newsorg.test/a',
                        'publishedAt' => '2025-01-01T00:00:00Z',
                        'category' => 'Tech',
                    ]
                ]
            ])
            ->push([
                'articles' => []
            ]);

        $svc = new NewsOrg('http://newsorg.test', 'key', ['q' => 'us'], 5);
        $articles = $svc->getArticles();
        $this->assertSame(1, $articles->count());
        $first = $articles->all()[0];
        $this->assertSame('News Org Title', $first->title);
        $this->assertSame('http://newsorg.test/a', $first->url);
    }
}
