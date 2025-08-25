<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::prefix('admin')->name('admin.')->group(function () {

    // Page principale / dashboard (redirige vers les produits)
    Route::get('/', function () {
        return redirect()->route('admin.products.index');
    })->name('dashboard');

    // Liste des produits (vue Blade)
    Route::get('products', [ProductController::class, 'indexView'])->name('products.index');

    // CRUD pour les produits, sauf index (déjà défini ci-dessus)
    Route::resource('products', ProductController::class)->except(['index']);
});
