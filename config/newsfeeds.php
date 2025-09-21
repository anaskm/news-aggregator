<?php


return [

    'providers' => [
        'news_org' => [
            'name' => 'News ORG',
            'api_key'  => env('NEWSORG_API_KEY'),
            'base_url' => env('NEWSORG_API_URL'),
        ],
        'nytimes' => [
            'name' => 'New York Times',
            'api_key'  => env('NYT_API_KEY'),
            'base_url' => env('NYT_API_URL'),
        ],
        'guardian' => [
            'name' => 'The Guardian',
            'api_key'  => env('GUARDIAN_API_KEY'),
            'base_url' => env('GUARDIAN_API_URL'),
        ],  
        'news_ai' => [
            'name' => 'News API',
            'api_key'  => env('NEWSAPI_API_KEY'),
            'base_url' => env('NEWSAPI_API_URL'),
            'url_id' => env('NEWSAPI_API_URL_ID'),
        ],
    ],
];