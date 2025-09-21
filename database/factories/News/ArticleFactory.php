<?php

namespace Database\Factories\News;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\News\Article;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'provider' => 'nytimes',
            'title' => $this->faker->sentence(6),
            'body' => $this->faker->paragraph(),
            'url' => $this->faker->url(),
            'category' => $this->faker->randomElement(['Tech','Sports','Business','General']),
            'published_at' => $this->faker->dateTimeBetween('-2 days', 'now'),
            'metadata' => [],
            'url_hash' => md5($this->faker->unique()->url()),
        ];
    }
}
