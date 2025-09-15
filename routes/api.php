<?php

use Illuminate\Support\Facades\Route;
use App\Adapter\Http\API\Auth\Controllers\LoginController;
use App\Adapter\Http\API\Auth\Controllers\AuthenticationController;
use App\Adapter\Http\API\Customer\Controllers\CartController;
use App\Adapter\Http\API\Customer\Controllers\ProductController;

// ------------------------
// Routes Authentification
// ------------------------
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', LoginController::class); 
    Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
});

// ------------------------
// Routes Produits publics
// ------------------------
Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/products/{id}', [ProductController::class, 'getProduct']);

// ------------------------
// Routes protégées (auth:sanctum)
// ------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Panier
    Route::prefix('customer')->group(function () {
        Route::post('/add-to-cart', [CartController::class, 'addToCart']);       // Ajouter au panier
        Route::post('/remove-from-cart', [CartController::class, 'removeFromCart']); // Retirer du panier
        Route::post('/update-quantity', [CartController::class, 'updateQuantity']); // Mettre à jour quantité
        Route::get('/cart', [CartController::class, 'getCart']);                  // Récupérer le panier
    });

    // Produits protégés (CRUD)
    Route::post('/products', [ProductController::class, 'createProduct']);
    Route::put('/products/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);

    // Favoris
    Route::get('/favorites', [ProductController::class, 'getFavorites']);
    Route::post('/favorites/toggle', [ProductController::class, 'toggleFavorite']);
});
