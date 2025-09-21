<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Auth\Contracts\JwtAuth;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Auth\Exceptions\ApiAuthException;

class JwtAuthMiddleware
{
    public function __construct(protected JwtAuth $jwtAuth)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->getBearerToken($request);
        $this->validateToken($token);
        
        return $next($request);
    }

    public function getBearerToken($request)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new ApiAuthException("Unauthorized", 401);
        }

        return substr($authHeader, 7);
    }

    public function validateToken($token)
    {
        try {
            $tokenData = $this->jwtAuth->validate($token);
            return $tokenData;
        } catch (\Exception $e) {
            throw new ApiAuthException("Unauthorized", 401);
        }

        return false;
    }
}
