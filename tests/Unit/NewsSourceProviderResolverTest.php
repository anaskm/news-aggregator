<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\News\NewsSourceProviderResolver;
use App\Services\News\Contracts\NewsService;
use Mockery;

class NewsSourceProviderResolverTest extends TestCase
{
    public function test_news_provider_resolver(): void
    {
        $resolver = new NewsSourceProviderResolver();
        $svc1 = Mockery::mock(NewsService::class);
        $svc2 = Mockery::mock(NewsService::class);

        $resolver->add('nytimes', $svc1);
        $resolver->add('news_org', $svc2);

        $this->assertSame($svc1, $resolver->get('nytimes'));
        $this->assertSame(['nytimes', 'news_org'], $resolver->getAllProviders());
    }
}
