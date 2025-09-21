<?php

return [
    // Secret key for HS* algorithms
    'secret' => env('JWT_SECRET', env('APP_KEY', 'base64:dummy')),

    // Algorithm, e.g., HS256, HS384, HS512
    'algo' => env('JWT_ALGO', 'HS256'),

    // Default TTL in seconds
    'ttl' => env('JWT_TTL', 3600),

    // Optional issuer and audience
    'iss' => env('JWT_ISS', null),
    'aud' => env('JWT_AUD', null),

    // Allowed clock skew in seconds
    'leeway' => env('JWT_LEEWAY', 0),
];
