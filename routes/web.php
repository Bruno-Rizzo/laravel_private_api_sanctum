<?php

use Illuminate\Support\Facades\Route;
use App\Services\ApiResponse;

Route::get('/', function () {
    return view('welcome');
});

Route::fallback(function(){
    return ApiResponse::error('Endpoint Not Found',400);
});
