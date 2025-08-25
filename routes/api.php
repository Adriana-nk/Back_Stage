<?php

use Illuminate\Support\Facades\Route;
use App\Adapter\Http\API\Auth\Controllers\RegisterController;
use App\Adapter\Http\API\Auth\Controllers\LoginController;
use App\Adapter\Http\API\Customer\Controllers\CartController;
use App\Adapter\Http\API\Customer\Controllers\FavoritesController;
use App\Adapter\Http\API\Customer\Controllers\ProductController;

// =======================
// Routes Auth
// =======================
Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
});

// =======================
// Routes Customer
// =======================
Route::prefix('customer')->group(function () {
    // Panier
    Route::post('/add-to-cart', [CartController::class, 'add']);
    Route::post('/remove-from-cart', [CartController::class, 'remove']);

    // Favoris
    Route::post('/add-to-favorites', [FavoritesController::class, 'add']);
    Route::post('/remove-from-favorites', [FavoritesController::class, 'remove']);

    // 🔹 Consultation
    // Route::get('/cart/{userId}', [CartController::class, 'get']);
    // Route::get('/favorites/{userId}', [FavoritesController::class, 'get']);
});

// =======================
// Routes Produits
// =======================
Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/products/{id}', [ProductController::class, 'getProduct']);

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'createProduct']);
    Route::put('/products/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);
});
