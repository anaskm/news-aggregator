<?php

namespace App\Services\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Services\Auth\Contracts\JwtAuth;

class FirebaseJwtService implements JwtAuth
{
    public function __construct(protected string $key)
    {
    }

    /**
     * Generate a JWT token from given claims.
     *
     * @param array $payload claims.
     * @param int|null $ttl time to live.
     * @return string JWT token.
     */
    public function generate(array $payload, ?int $ttl = 3600): string
    {
        $issuedAt = time();
        $payload = array_merge([
            'iat' => $issuedAt,
            'exp' => $issuedAt + $ttl,
        ], $payload);

        return JWT::encode($payload, $this->key, 'HS256');
    }

    /**
     * Validate a JWT token and return its decoded payload.
     *
     * @param string $token jwt token.
     * @return array decoded payload claims.
     */
    public function validate(string $token): array
    {
        $decoded = JWT::decode($token, new Key($this->key, 'HS256'));

        return json_decode(json_encode($decoded), true, 512, JSON_THROW_ON_ERROR);
    }
}
