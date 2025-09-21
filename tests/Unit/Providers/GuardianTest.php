<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\News\Providers\Guardian;

class GuardianTest extends TestCase
{
    public function test_get_articles(): void
    {
        Http::fakeSequence()
            ->push([
                'response' => [
                    'results' => [
                        [
                            'webTitle' => 'Guardian Title',
                            'fields' => [
                                'bodyText' => 'Guardian body sample',
                            ],
                            'webUrl' => 'http://guardian.test/a',
                            'webPublicationDate' => '2025-02-02T00:00:00Z',
                            'sectionName' => 'Business',
                        ],
                    ],
                ],
            ])
            ->push([
                'response' => [
                    'results' => [],
                ],
            ]);

        $svc = new Guardian('http://guardian.test', 'key', [], 5);
        $articles = $svc->getArticles();

        $this->assertSame(1, $articles->count());
        $first = $articles->all()[0];
        $this->assertSame('Guardian Title', $first->title);
        $this->assertSame('Guardian body sample', $first->description);
        $this->assertSame('http://guardian.test/a', $first->url);
        $this->assertSame('2025-02-02T00:00:00Z', $first->publishedAt);
        $this->assertSame('Business', $first->category);
    }
}
