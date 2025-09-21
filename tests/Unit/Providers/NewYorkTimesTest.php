<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\News\Providers\NewYorkTimes;

class NewYorkTimesTest extends TestCase
{
    public function test_get_articles(): void
    {
        Http::fakeSequence()
            ->push([
                'response' => [
                    'docs' => [
                        [
                            'headline' => ['main' => 'NYT Title'],
                            'snippet' => 'NYT Snippet Sample',
                            'web_url' => 'http://nyt.test/a',
                            'pub_date' => '2025-01-01T00:00:00Z',
                            'subsection_name' => 'News',
                        ],
                    ],
                ],
            ])
            ->push([
                'response' => [
                    'docs' => [],
                ],
            ]);

        $svc = new NewYorkTimes('http://nyt.test', 'key', [], 5);
        $articles = $svc->getArticles();

        $this->assertSame(1, $articles->count());
        $first = $articles->all()[0];
        $this->assertSame('NYT Title', $first->title);
        $this->assertSame('NYT Snippet Sample', $first->description);
        $this->assertSame('http://nyt.test/a', $first->url);
        $this->assertSame('2025-01-01T00:00:00Z', $first->publishedAt);
        $this->assertSame('News', $first->category);
    }
}
