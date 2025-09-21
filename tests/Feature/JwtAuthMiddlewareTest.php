<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Services\Auth\Contracts\JwtAuth;

class JwtAuthMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        Route::middleware('auth.jwt')->get('/_test/protected', function () {
            return response()->json(['ok' => true]);
        });
    }

    public function test_missing_header(): void
    {
        $this->getJson('/_test/protected')->assertStatus(401)
            ->assertJson([
                'status' => 'ERROR',
                'message' => 'Unauthorized',
            ]);
    }

    public function test_invalid_header(): void
    {
        $this->withHeaders(['Authorization' => 'Token abc'])
            ->getJson('/_test/protected')
            ->assertStatus(401)
            ->assertJson([
                'status' => 'ERROR',
                'message' => 'Unauthorized',
            ]);
    }

    public function test_invalid_token(): void
    {
        $this->app->bind(JwtAuth::class, function () {
            return new class implements JwtAuth {
                public function generate(array $payload, ?int $ttl = null): string { return 'fake'; }
                public function validate(string $token): array { throw new \RuntimeException('bad'); }
            };
        });

        $this->withHeaders(['Authorization' => 'Bearer invalid.token'])
            ->getJson('/_test/protected')
            ->assertStatus(401)
            ->assertJson([
                'status' => 'ERROR',
                'message' => 'Unauthorized',
            ]);
    }

    public function test_valid_token(): void
    {
        $this->app->bind(JwtAuth::class, function () {
            return new class implements JwtAuth {
                public function generate(array $payload, ?int $ttl = null): string { return 'valid'; }
                public function validate(string $token): array { return ['sub' => 1]; }
            };
        });

        $this->withHeaders(['Authorization' => 'Bearer some.valid.token'])
            ->getJson('/_test/protected')
            ->assertStatus(200)
            ->assertJson(['ok' => true]);
    }
}
