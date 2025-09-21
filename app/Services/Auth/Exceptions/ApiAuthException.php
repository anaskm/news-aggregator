<?php

namespace App\Services\Auth\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiAuthException extends Exception
{
    protected int $statusCode;

    public function __construct(string $message, int $statusCode = 401)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function report(): void
    {
        //skip laravel logging
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'status'  => 'ERROR',
            'message' => $this->getMessage(),
        ], $this->statusCode);
    }
}
