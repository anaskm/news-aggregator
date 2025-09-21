<?php

use Illuminate\Support\Facades\Route;

Route::get('/articles', [\App\Http\Controllers\Api\v1\ArticleController::class, 'index']);

Route::get('/articles/{id}', [\App\Http\Controllers\Api\v1\ArticleController::class, 'show']);