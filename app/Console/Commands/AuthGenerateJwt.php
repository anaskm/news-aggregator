<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Auth\Contracts\JwtAuth;

class AuthGenerateJwt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:generate-jwt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate jwt token for auth';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jwtService = app(JwtAuth::class);
        $ttl = strtotime('+30 days');

        $token = $jwtService->generate([
            'scope' => 'articles',
            'iss' => config('jwt.issuer'),
            'rd' => bin2hex(random_bytes(20)),
            'iat' => time(),
            'nbf' => time(),
            'exp'    =>  $ttl,
        ], $ttl);

        $this->info("Generated JWT Token:");
        $this->warn("{$token}");
    }
}
