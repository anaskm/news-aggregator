<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth.jwt',
], function () {

    Route::group([
        'prefix' => 'v1',
    ], function () {
        require base_path('routes/api_routes/v1.php');
    });
});