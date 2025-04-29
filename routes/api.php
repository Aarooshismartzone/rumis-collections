<?php

use App\Http\Controllers\api\customerAuthController;
use App\Http\Controllers\api\productController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/generate-guest-token', [customerAuthController::class, 'generateGuestToken']);

// Require token (auth or guest) for all product-related APIs
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categories', [productController::class, 'getCategories']);
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::get('/products/{id}', [ProductController::class, 'getProductById']);
});