<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'title',
        'body',
        'url',
        'category',
        'published_at',
        'metadata',
        'url_hash'
    ];

    protected $casts = [
        'metadata' => 'array',
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'provider_name',
    ];

    protected function providerName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => config("newsfeeds.providers.{$attributes['provider']}.name", $attributes['provider']),
        );
    }
}
