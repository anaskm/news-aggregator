<?php

namespace App\Services\Auth\Contracts;

interface JwtAuth
{
    /**
     * Generate a JWT token from given claims.
     *
     * @param array $payload claims.
     * @param int|null $ttl time to live.
     * @return string JWT token.
     */
    public function generate(array $payload, ?int $ttl = null): string;

    /**
     * Validate a JWT token and return its decoded payload.
     *
     * @param string $token jwt token.
     * @return array decoded payload claims.
     * 
     * @throws \RuntimeException if token is invalid or expired.
     */
    public function validate(string $token): array;
}
