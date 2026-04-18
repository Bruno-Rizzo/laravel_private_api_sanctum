<?php

use Illuminate\Support\Facades\Route;
use App\Services\ApiResponse;


Route::prefix('v1')->group(function(){
    require base_path('/routes/api_v1.php');
});

Route::fallback(function(){
    return ApiResponse::error('Endpoint Not Found',400);
});

