<?php

use App\Http\Controllers\v1\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\MainController;
use App\Services\ApiResponse;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){

Route::get('/status', [MainController::class, 'status']);

Route::get('/categories',                [MainController::class, 'listCategories']);
Route::get('/categories/{id}',           [MainController::class, 'getCategory']);
Route::get('/categories/{id}/products',  [MainController::class, 'getProductsByCategory']);
Route::post('/categories/create',        [MainController::class, 'createCategory']);
Route::put('/categories/{id}/update',    [MainController::class, 'updateCategory']);
Route::delete('/categories/{id}/delete', [MainController::class, 'deleteCategory']);


Route::get('/products',                [MainController::class, 'listProducts']);
Route::get('/products/{id}',           [MainController::class, 'getProduct']);
Route::post('/products/create',        [MainController::class, 'createProduct']);
Route::put('/products/{id}/update',    [MainController::class, 'updateProduct']);
Route::delete('/products/{id}/delete', [MainController::class, 'deleteProduct']);


Route::get('/movements',                             [MainController::class, 'listMovements']);
Route::get('/movements/ordered/{field}/{direction}', [MainController::class, 'listMovementsOrdered']);
Route::post('/movements/create',                     [MainController::class, 'createMovement']);
Route::put('/movements/{id}/update',                 [MainController::class, 'updateMovement']);
Route::delete('/movements/{id}/delete',              [MainController::class, 'deleteMovement']);


});






