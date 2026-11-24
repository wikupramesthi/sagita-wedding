<?php

use App\Http\Middleware\NormalizeApiResponse;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware(NormalizeApiResponse::class)
    ->group(__DIR__.'/api_v1.php');

Route::group([], __DIR__.'/api_v1.php');
